<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\CashTransactionCategory;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CashTransactionController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::CASH_TRANSACTION_MANAGEMENT);
    }

    public function index()
    {
        $items = CashTransaction::with(['account', 'category'])->orderBy('id', 'desc')->get();
        $account_by_ids = [];
        $accounts = CashAccount::all();
        foreach ($accounts as $account) {
            $account_by_ids[$account->id] = $account;
        }
        return view('admin.cash-transaction.index', compact('items', 'account_by_ids'));
    }

    public function edit(Request $request, $id = 0)
    {
        if ($id) {
            $item = CashTransaction::findOrFail($id);
        } else {
            $item = new CashTransaction();
            $item->date = current_date();
        }
        $item->type = $item->amount < 0 ? 'expense' : 'income';

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'description' => 'required',
                'date' => 'required',
                'account_id' => 'required',
            ], [
                'description.required' => 'Deskripsi harus diisi.',
                'date.required' => 'Tanggal harus diisi.',
                'account_id.required' => 'Akun harus dipilih.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            DB::beginTransaction();

            $data = ['Old Data' => $item->toArray()];
            if ($item->account) {
                $item->account->balance -= $item->amount;
                $item->account->save();
            }

            $item->fill($request->except('type'));
            unset($item->type);

            if (empty($item->category_id)) {
                $item->category_id = null;
            }

            $item->amount = number_from_input($item->amount);
            if ($request->type == 'expense') {
                $item->amount = -$item->amount;
            }

            $item->save();

            $account = CashAccount::find($item->account_id);
            $account->balance += $item->amount;
            $account->save();

            $data['New Data'] = $item->toArray();
            UserActivity::log(
                UserActivity::CASH_TRANSACTION_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Kategori Produk',
                'Kategori Produk ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );
            DB::commit();

            return redirect('admin/cash-transaction')->with('info', 'Kategori transaksi telah disimpan.');
        }
        $categories = CashTransactionCategory::orderBy('id', 'asc');
        $accounts = CashAccount::where('active', '=', 1)->orderBy('name', 'asc')->get();
        return view('admin.cash-transaction.edit', compact('item', 'categories', 'accounts'));
    }

    public function delete($id)
    {
        $item = CashTransaction::findOrFail($id);
        $account = CashAccount::find($item->account_id);
        $account->balance -= $item->amount;
        $message = 'Transaksi ' . e($item->idFormatted()) . ' telah dihapus.';

        DB::beginTransaction();
        $item->delete();
        $account->save();
        UserActivity::log(
            UserActivity::CASH_TRANSACTION_MANAGEMENT,
            'Hapus Transaksi Keuangan',
            $message,
            $item->toArray()
        );
        DB::commit();

        return redirect('admin/cash-transaction')->with('info', $message);
    }
}
