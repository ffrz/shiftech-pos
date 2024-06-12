<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Customer;
use App\Models\Party;
use App\Models\Product;
use App\Models\StockUpdate;
use App\Models\StockUpdateDetail;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesOrderController extends Controller
{

    public function index(Request $request)
    {
        $filter_active = false;

        $filter = [
            'status'   => (int)$request->get('status', $request->session()->get('sales-order.filter.status', -1)),
            'datetime' => $request->get('datetime', $request->session()->get('sales-order.filter.datetime', 'today')),
            'search'   => $request->get('search', $request->session()->get('sales-order.filter.search', '')),
        ];

        if ($request->get('action') == 'reset') {
            $filter['status'] = -1;
            $filter['datetime'] = 'today';
        }

        $q = StockUpdate::query();
        if ($filter['status'] != -1) {
            $filter_active = true;
            $q->where('status', '=', $filter['status']);
        }

        if ($filter['datetime'] <> 'all') {
            $filter_active = true;
            if ($filter['datetime'] == 'today') {
                $datetime = datetime_range_today();
            } else if ($filter['datetime'] == 'yesterday') {
                $datetime = datetime_range_yesterday();
            } else if ($filter['datetime'] == 'this-week') {
                $datetime = datetime_range_this_week();
            } else if ($filter['datetime'] == 'prev-week') {
                $datetime = datetime_range_previous_week();
            } else if ($filter['datetime'] == 'this-month') {
                $datetime = datetime_range_this_month();
            } else if ($filter['datetime'] == 'prev-month') {
                $datetime = datetime_range_previous_month();
            }
            $q->whereRaw("(datetime between '$datetime[0]' and '$datetime[1]')");
        }

        if (!empty($filter['search'])) {
            $q->where('party_name', 'like', '%' . $filter['search'] . '%');
        }

        $items = $q->with('party')
            ->whereRaw('type = ' . StockUpdate::TYPE_SALES_ORDER)
            ->orderBy('id', 'desc')
            ->paginate(10);

        $request->session()->put('sales-order.filter.datetime', $filter['datetime']);
        $request->session()->put('sales-order.filter.status', $filter['status']);
        $request->session()->put('sales-order.filter.search', $filter['search']);

        return view('admin.sales-order.index', compact('items', 'filter', 'filter_active'));
    }

    public function create()
    {
        $item = new StockUpdate();
        $item->datetime = current_datetime();
        $item->type = StockUpdate::TYPE_SALES_ORDER;
        $item->status = StockUpdate::STATUS_OPEN;
        $item->id2 = StockUpdate::getNextId2($item->type);
        $item->save();
        return redirect('admin/sales-order/edit/' . $item->id)->with('info', 'Order penjualan telah dibuat.');
    }

    public function edit(Request $request, $id = 0)
    {
        $item = StockUpdate::findOrFail($id);
        if (!$item) {
            return redirect('admin/sales-order')->with('warning', 'Order penjualan tidak ditemukan.');
        }

        if ($request->method() == 'POST') {
            $data = ['Old Data' => $item->toArray()];
            $item->fill($request->all());

            if (empty($item->party_id)) {
                $item->party_id = null;
            }

            $product_by_ids = [];
            if (!empty($request->product_id)) {
                $products = Product::whereIn('id', $request->product_id)->get();
                foreach ($products as $product) {
                    $product_by_ids[$product->id] = $product;
                }
            }

            DB::beginTransaction();
            if (!$item->party_id) {
                if (!empty($item->party_name)) {

                    $party = new Party();
                    $party->type = Party::TYPE_CUSTOMER;
                    $party->name = $request->party_name;
                    $party->phone = $request->party_phone;
                    $party->address = $request->party_address;
                    $party->id2 = $party->getNextId2($party->type);
                    $party->save();
                    $item->party_id = $party->id;
                }
            }

            if ($request->action == 'complete' || $request->action == 'cancel') {
                $item->status = $request->action == 'complete' ? StockUpdate::STATUS_COMPLETED : StockUpdate::STATUS_CANCELED;
                $item->closed_datetime = current_datetime();
                $item->closed_by_uid = current_user_id();
            } else {
                $item->updated_datetime = current_datetime();
                $item->updated_by_uid = current_user_id();
            }

            $item->total_cost = 0;
            $item->total_price = 0;

            DB::delete('delete from stock_update_details where update_id = ?', [$item->id]);
            if (!empty($request->product_id)) {
                foreach ($request->product_id as $row_id => $product_id) {
                    $product = $product_by_ids[$product_id];
                    $d = new StockUpdateDetail();
                    $d->id = $row_id;
                    $d->update_id = $item->id;
                    $d->product_id = $product_id;
                    $d->quantity = -number_from_input($request->qty[$row_id]);
                    $d->cost = $product->cost;
                    $d->stock_before = $product->stock;
                    $d->price = number_from_input($request->price[$row_id]);
                    $item->total_cost += ($d->cost * $d->quantity);
                    $item->total_price += ($d->price * $d->quantity);

                    // saat ini belum ada diskon dan pajak, cukup set total dari total harga
                    $item->total = $item->total_price;

                    $d->save();
                }
            }

            if ($item->status == StockUpdate::STATUS_COMPLETED) {
                $details = StockUpdateDetail::with('product')->whereRaw('update_id=' . $item->id)->get();
                foreach ($details as $detail) {
                    $product = $detail->product;
                    $product->stock += $detail->quantity; // qty negative
                    $product->save();
                }
            }

            $item->save();

            $data['New Data'] = $item->toArray();

            DB::commit();

            if ($item->status == StockUpdate::STATUS_OPEN) {
                return redirect('admin/sales-order/edit/' . $item->id)->with('info', 'Order penjualan telah disimpan.');
            }

            return redirect('admin/sales-order/detail/' . $item->id)->with('info', 'Order penjualan telah selesai.');
        }

        $tmp_products = Product::select(['id', 'code', 'description', 'stock', 'uom', 'price', 'barcode'])
            ->whereRaw('active=1')
            ->orderBy('code', 'asc')->get();
        $products = [];
        $product_code_by_ids = [];
        $barcodes = [];
        foreach ($tmp_products as $product) {
            $p = $product->toArray();
            $p['pid'] = $product->idFormatted();
            $product_code_by_ids[$product->id] = $p['pid'];
            $products[$product->idFormatted()] = $p;
            if (!empty($product->barcode)) {
                $barcodes[$product->barcode] = $product->idFormatted();
            }
        }
        $parties = Party::where('type', '=', Party::TYPE_CUSTOMER)
            ->whereRaw('active=1')
            ->orderBy('name', 'asc')
            ->get();
        $details = $item->details;
        return view('admin.sales-order.edit', compact('item', 'parties', 'products', 'barcodes', 'details', 'product_code_by_ids'));
    }

    public function detail(Request $request, $id)
    {
        $item = StockUpdate::with(['created_by', 'closed_by'])->find($id);
        $details = StockUpdateDetail::with(['product'])->where('update_id', '=', $item->id)->get();
        if ($request->get('print') == 1) {
            return view('admin.sales-order.print', compact('item', 'details'));
        } else if ($request->get('print') == 2) {
            return view('admin.sales-order.print-small', compact('item', 'details'));
        }
        return view('admin.sales-order.detail', compact('item', 'details'));
    }
}
