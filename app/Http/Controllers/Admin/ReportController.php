<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::VIEW_REPORTS);
    }

    public function index()
    {
        return view('admin.report.index');
    }

    public function inventoryStock()
    {
        $items = Product::where('type', '=', Product::STOCKED)
            ->orderBy('code', 'asc')
            ->get();

        return view('admin.report.inventory-stock', compact('items'));
    }

    public function inventoryMinimumStock()
    {
        $items = Product::where('type', '=', Product::STOCKED)
            ->whereRaw('stock < minimum_stock')
            ->orderBy('code', 'asc')
            ->get();

        return view('admin.report.inventory-minimum-stock', compact('items'));
    }

    public function inventoryStockRecapByCategory()
    {
        $records = ProductCategory::orderBy('name', 'asc')->get();
        $uncategorized = [
            'name' => 'Tanpa Kategori',
            'total_cost' => 0,
            'total_price' => 0,
        ];
        $categories = [0 => $uncategorized];
        foreach ($records as $cat) {
            $categories[$cat->id] = [
                'name' => $cat->name,
                'total_cost' => 0,
                'total_price' => 0,
            ];
        }

        $products = Product::where('type', '=', Product::STOCKED)
            ->orderBy('code', 'asc')
            ->get();

        $total_cost = 0;
        $total_price = 0;
        foreach ($products as $product) {
            $subtotal_cost = $product->stock * $product->cost;
            $subtotal_price = $product->stock * $product->price;
            $categories[$product->category_id]['total_cost'] += $subtotal_cost;
            $categories[$product->category_id]['total_price'] += $subtotal_price;
            $total_cost += $subtotal_cost;
            $total_price += $subtotal_price;
        }

        $data = [
            'categories' => $categories,
            'total_cost' => $total_cost,
            'total_price' => $total_price,
        ];

        return view('admin.report.inventory-stock-recap-by-category', compact('data'));
    }

    public function inventoryStockDetailByCategory()
    {
        $records = ProductCategory::orderBy('name', 'asc')->get();
        $uncategorized = [
            'name' => 'Tanpa Kategori',
            'total_cost' => 0,
            'total_price' => 0,
            'products' => [],
        ];
        $categories = [0 => $uncategorized];
        foreach ($records as $cat) {
            $categories[$cat->id] = [
                'name' => $cat->name,
                'total_cost' => 0,
                'total_price' => 0,
                'products' => [],
            ];
        }

        $products = Product::where('type', '=', Product::STOCKED)
            ->orderBy('code', 'asc')
            ->get();

        $total_cost = 0;
        $total_price = 0;
        foreach ($products as $product) {
            $subtotal_cost = $product->stock * $product->cost;
            $subtotal_price = $product->stock * $product->price;
            $categories[$product->category_id]['total_cost'] += $subtotal_cost;
            $categories[$product->category_id]['total_price'] += $subtotal_price;
            $total_cost += $subtotal_cost;
            $total_price += $subtotal_price;

            if (!isset($categories[$product->category_id]['products'])) {
                $categories[$product->category_id]['products'] = [];
            }
            $categories[$product->category_id]['products'][] = $product;
        }

        $data = [
            'categories' => $categories,
            'total_cost' => $total_cost,
            'total_price' => $total_price,
        ];

        return view('admin.report.inventory-stock-detail-by-category', compact('data'));
    }

    public function monthlyExpenseDetail(Request $request)
    {
        if (!$request->has('period')) {
            return view('admin.report.expense.expense-detail');
        }

        $period = extract_daterange_from_input($request->get('period'), date('01-m-Y') . ' - ' . date('t-m-Y'));

        $startDate = datetime_from_input($period[0]);
        $endDate = datetime_from_input($period[1]);

        $q = Expense::with('category')
            ->whereRaw("(date between '$startDate' and '$endDate')");
        $q->orderBy('id', 'asc');

        $items = $q->get();
        $categories = ExpenseCategory::query()->orderBy('name', 'asc')->get();

        return view('admin.report.expense.print-expense-detail', compact('items', 'categories', 'period'));
    }

    public function monthlyExpenseRecap(Request $request)
    {
        if (!$request->has('period')) {
            return view('admin.report.expense.expense-recap');
        }

        $period = extract_daterange_from_input($request->get('period'), date('01-m-Y') . ' - ' . date('t-m-Y'));

        $startDate = datetime_from_input($period[0]);
        $endDate = datetime_from_input($period[1]);

        $q = Expense::whereRaw("(date between '$startDate' and '$endDate')")->orderBy('id', 'asc');

        $expenses = $q->get();

        $categories = ExpenseCategory::query()->orderBy('name', 'asc')->get();
        $categoryByIds = [];

        foreach ($categories as $category) {
            $categoryByIds[$category->id] = $category;
            $category->total = 0;
        }

        foreach ($expenses as $expense) {
            $categoryByIds[$expense->category_id]->total += $expense->amount;
        }

        $items = $categoryByIds;

        return view('admin.report.expense.print-expense-recap', compact('items', 'categories', 'period'));
    }
}
