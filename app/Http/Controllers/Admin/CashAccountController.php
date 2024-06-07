<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CashAccountController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::CASH_ACCOUNT_MANAGEMENT);
    }

    public function index()
    {
        $items = CashAccount::orderBy('name', 'asc')->get();
        return view('admin.cash-account.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        if (!$id) {
            $item = new CashAccount();
            $item->active = true;
            $item->type = 0;
        }
        else {
            $item = CashAccount::findOrFail($id);
        }

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:cash_accounts,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama akun harus diisi.',
                'name.unique' => 'Nama akun sudah digunakan.',
                'name.max' => 'Nama akun terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $item->toArray()];
            $oldBalance = $item->balance;
            $item->fill($request->all());
            $item->balance = number_from_input($request->balance);

            $isNew = !$item->id;

            DB::beginTransaction();
            $item->save();
            if ($isNew || ($oldBalance != $item->balance)) {
                $amount = $item->balance - $oldBalance;
                $transaction = new CashTransaction();
                $transaction->account_id = $item->id;
                $transaction->date = current_date();
                $transaction->type = $isNew ? CashTransaction::TYPE_INITIAL_BALANCE : CashTransaction::TYPE_ADJUSTMENT;
                $transaction->amount = $amount;
                $transaction->description = $isNew ? 'Saldo awal' : 'Penyesuaian saldo manual';
                $transaction->save();
            }

            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::CASH_ACCOUNT_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Akun Kas / Rekening',
                'Akun ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );
            DB::commit();

            return redirect('admin/cash-account')->with('info', 'Akun telah disimpan.');
        }

        return view('admin.cash-account.edit', compact('item'));
    }

    public function delete($id)
    {
        if (!$item = CashAccount::find($id)) {
            $message = 'Akun tidak ditemukan.';
        } else if ($item->delete($id)) {
            $message = 'Akun ' . e($item->name) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::CASH_ACCOUNT_MANAGEMENT,
                'Hapus Akun',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/cash-account')->with('info', $message);
    }
}
