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
    public function index(Request $request)
    {
        ensure_user_can_access(AclResource::CASH_TRANSACTION_LIST);

        $filter = [
            'account_id' => (int)$request->get('account_id', $request->session()->get('cash-transaction.filter.account_id', -1)),
            'date' => $request->get('date', $request->session()->get('cash-transaction.filter.date', current_date())),
        ];

        $actual_balance = 0;

        $q = CashTransaction::with(['account', 'category']);
        if ($filter['account_id'] != -1) {
            $q->where('account_id', '=', $filter['account_id']);

            $selectedAccountId = CashAccount::find($filter['account_id']);
            if ($selectedAccountId) {
                $actual_balance = $selectedAccountId->balance;
            }
        }
        if ($filter['date'] != null) {
            $q->where('date', '=', $filter['date']);
        }

        $request->session()->put('cash-transaction.filter.account_id', $filter['account_id']);
        $request->session()->put('cash-transaction.filter.date', $filter['date']);

        $items = $q->orderBy('id', 'desc')->get();
        $account_by_ids = [];
        $accounts = CashAccount::all();
        foreach ($accounts as $account) {
            $account_by_ids[$account->id] = $account;
        }

        return view('admin.cash-transaction.index', compact('items', 'account_by_ids', 'accounts', 'filter', 'actual_balance'));
    }

    public function edit(Request $request, $id = 0)
    {
        if (!$id) {
            ensure_user_can_access(AclResource::ADD_CASH_TRANSACTION);
            $item = new CashTransaction();
            $item->date = current_date();
        } else {
            ensure_user_can_access(AclResource::EDIT_CASH_TRANSACTION);
            $item = CashTransaction::findOrFail($id);
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
        $categories = CashTransactionCategory::orderBy('id', 'asc')->get();
        $accounts = CashAccount::where('active', '=', 1)->orderBy('name', 'asc')->get();
        return view('admin.cash-transaction.edit', compact('item', 'categories', 'accounts'));
    }

    public function delete($id)
    {
        ensure_user_can_access(AclResource::DELETE_CASH_TRANSACTION);
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
