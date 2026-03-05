<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Models\MainCategoryModel;
use App\Models\SubCategoryModel;
use App\Models\orderModel;
use App\Models\CustomerModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
   public function dashboard()
    {
        // ================= TOTAL SALES =================
        //$totalSales = orderModel::sum('total') ?? 0;
        $totalSales = orderModel::where('status','completed')
                        ->sum('total');


        // ================= TOTAL ORDERS =================
        $totalOrders = orderModel::count() ?? 0;

        // ================= TOTAL CUSTOMERS =================
        $totalCustomers = CustomerModel::count()?? 0;

        // ================= TOTAL REVENUE =================
        $totalRevenue = orderModel::sum('total') ?? 0;

        $monthlyTarget = 200; // set your target
        $thisMonthSales = orderModel::whereMonth('created_at', Carbon::now()->month)
                            ->sum('total');

        $salesProgress = $monthlyTarget > 0 
            ? ($thisMonthSales / $monthlyTarget) * 100 
            : 0;

        // ===============================
        // TOP PERFORMING CATEGORIES
        // ===============================
        $topCategories = DB::table('order_items')
            ->join('products','order_items.product_id','=','products.id')
            ->join('main_categories','products.main_category_id','=','main_categories.id')
            ->select('main_categories.Maincategoryname',
                     DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'))
            ->groupBy('main_categories.Maincategoryname')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        // ===============================
        // RECENT ORDERS ACTIVITY
        // ===============================
        $recentOrders = orderModel::latest()
                            ->limit(5)
                            ->get();

        // // ================= LAST WEEK SALES =================
        // $lastWeekSales = orderModel::whereBetween('created_at', [
        //     Carbon::now()->subWeek(),
        //     Carbon::now()
        // ])->sum('total') ?? 0;

          // Example: last 7 days sales
    $salesAnalytics = orderModel::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(total) as total_sales')
    )
    ->where('status', 'completed') // only completed orders
    ->where('created_at', '>=', now()->subDays(7))
    ->groupBy('date')
    ->orderBy('date')
    ->get();

    // Convert to arrays for chart
    $dates = $salesAnalytics->pluck('date');
    $totals = $salesAnalytics->pluck('total_sales');


        return view('Admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'totalRevenue',
             'dates',
            'totals',
            'salesProgress',
            'topCategories',
            'recentOrders'
        ));
    }
}
