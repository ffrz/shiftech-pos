<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\AclResource;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockUpdate;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesReportController extends Controller
{
    public function __construct()
    {
        ensure_user_can_access(AclResource::VIEW_REPORTS);
    }

    public function netIncomeStatement(Request $request)
    {
        if (!$request->has('period')) {
            return view('admin.report.sales.net-income-statement');
        }

        $period = extract_daterange_from_input($request->get('period'), date('01-m-Y') . ' - ' . date('t-m-Y'));

        $startDate = datetime_from_input($period[0]);
        $endDate = datetime_from_input($period[1]);
        $status = StockUpdate::STATUS_COMPLETED;
        $sales = StockUpdate::TYPE_SALES_ORDER;

        // Disini kita baru menghitung total harga di penjualan dikurangi total modal, belum menghitung laba rugi di retur penjualan dan laba / rugi dari selisih stok
        $total_sales = DB::scalar(
            "select abs(sum(total_price)) from stock_updates where (date(datetime) between '$startDate' and '$endDate') and status=$status and type=$sales"
        );

        $total_cost = DB::scalar(
            "select abs(sum(total_cost)) from stock_updates where (date(datetime) between '$startDate' and '$endDate') and status=$status and type=$sales"
        );

        return view('admin.report.sales.print-net-income-statement', compact('total_cost', 'total_sales', 'period'));
    }
}
