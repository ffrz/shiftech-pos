<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\CashTransactionCategory;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashTransactionCategoryController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::CASH_TRANSACTION_CATEGORY_MANAGEMENT);
    }

    public function index()
    {
        $items = CashTransactionCategory::orderBy('name', 'asc')->get();
        return view('admin.cash-transaction-category.index', compact('items'));
    }

    public function edit(Request $request, $id = 0)
    {
        $item = $id ? CashTransactionCategory::find($id) : new CashTransactionCategory();
        if (!$item)
            return redirect('admin/cash-transaction-category')->with('warning', 'Kategori transaksi tidak ditemukan.');

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:CASH_TRANSACTION_categories,name,' . $request->id . '|max:100',
            ], [
                'name.required' => 'Nama kategori harus diisi.',
                'name.unique' => 'Nama kategori sudah digunakan.',
                'name.max' => 'Nama kategori terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $item->toArray()];
            $item->fill($request->all());
            $item->save();
            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::CASH_TRANSACTION_CATEGORY_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Kategori Produk',
                'Kategori Produk ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/cash-transaction-category')->with('info', 'Kategori transaksi telah disimpan.');
        }

        return view('admin.cash-transaction-category.edit', compact('item'));
    }

    public function delete($id)
    {
        if (!$item = CashTransactionCategory::find($id)) {
            $message = 'Kategori tidak ditemukan.';
        } else if ($item->delete($id)) {
            $message = 'Kategori ' . e($item->name) . ' telah dihapus.';
            UserActivity::log(
                UserActivity::CASH_TRANSACTION_CATEGORY_MANAGEMENT,
                'Hapus Kategori',
                $message,
                $item->toArray()
            );
        }

        return redirect('admin/cash-transaction-category')->with('info', $message);
    }
}
