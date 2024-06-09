<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Customer;
use App\Models\Party;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        ensure_user_can_access(AclResource::CUSTOMER_LIST);

        $filter = [
            'active' => (int)$request->get('active', 1),
            'search' => $request->get('search', ''),
        ];

        $q = Customer::query();
        $q->where('type', '=', Party::TYPE_CUSTOMER);

        if ($filter['active'] != -1) {
            $q->where('active', '=', $filter['active']);
        }

        if (!empty($filter['search'])) {
            $q->where('id2', '=', $filter['search']);
            $q->orWhere('name', 'like', '%' . $filter['search'] . '%');
            $q->orWhere('phone', 'like', '%' . $filter['search'] . '%');
            $q->orWhere('address', 'like', '%' . $filter['search'] . '%');
        }

        $items = $q->orderBy('name', 'asc')->paginate(10);
        return view('admin.customer.index', compact('items', 'filter'));
    }

    public function edit(Request $request, $id = 0)
    {
        if (!$id) {
            ensure_user_can_access(AclResource::ADD_CUSTOMER);
            $item = new Customer();
            $item->id2 = Party::getNextId2(Party::TYPE_CUSTOMER);
            $item->active = true;
        } else {
            ensure_user_can_access(AclResource::EDIT_CUSTOMER);
            $item = Customer::find($id);
            if (!$item) {
                return redirect('admin/customer')->with('warning', 'Pelanggan tidak ditemukan.');
            }
        }

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100',
            ], [
                'name.required' => 'Nama pelanggan harus diisi.',
                'name.max' => 'Nama pelanggan terlalu panjang, maksimal 100 karakter.',
            ]);

            if ($validator->fails())
                return redirect()->back()->withInput()->withErrors($validator);

            $data = ['Old Data' => $item->toArray()];

            $tmpData = $request->all();
            if (empty($tmpData['active']))
                $tmpData['active'] = false;

            $item->fill($tmpData);
            $item->save();
            $data['New Data'] = $item->toArray();

            UserActivity::log(
                UserActivity::CUSTOMER_MANAGEMENT,
                ($id == 0 ? 'Tambah' : 'Perbarui') . ' Pelanggan',
                'Pelanggan ' . e($item->name) . ' telah ' . ($id == 0 ? 'dibuat' : 'diperbarui'),
                $data
            );

            return redirect('admin/customer')->with('info', 'Pelanggan telah disimpan.');
        }

        return view('admin.customer.edit', compact('item'));
    }

    public function delete($id)
    {
        ensure_user_can_access(AclResource::DELETE_CUSTOMER);

        $item = Customer::findOrFail($id);

        $message = 'Pelanggan ' . e($item->name) . ' telah dihapus.';
        UserActivity::log(
            UserActivity::CUSTOMER_MANAGEMENT,
            'Hapus Pelanggan',
            $message,
            $item->toArray()
        );

        return redirect('admin/customer')->with('info', $message);
    }

    public function detail(Request $request, $id)
    {
        $item = Customer::findOrFail($id);
        return view('admin.customer.detail', compact('item'));
    }
}
