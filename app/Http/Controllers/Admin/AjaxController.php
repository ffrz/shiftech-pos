<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use App\Models\Party;
use App\Models\ProductCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function addProductCategory(Request $request)
    {
        $category = new ProductCategory($request->all());
        $category->save();
        return response()->json([
            'status' => 'success',
            'data' => $category,
            'message' => 'Kategori baru telah ditambahkan.'
        ], 200);
    }

    public function addExpenseCategory(Request $request)
    {
        $category = new ExpenseCategory($request->all());
        $category->save();
        return response()->json([
            'status' => 'success',
            'data' => $category,
            'message' => 'Kategori baru telah ditambahkan.'
        ], 200);
    }

    public function addSupplier(Request $request)
    {
        $supplier = new Supplier();
        $supplier->fill($request->all());
        $supplier->id2 = Party::getNextId2(Party::TYPE_SUPPLIER);
        $supplier->save();
        return response()->json([
            'status' => 'success',
            'data' => $supplier,
            'message' => 'Supplier baru telah ditambahkan.'
        ], 200);
    }
}
