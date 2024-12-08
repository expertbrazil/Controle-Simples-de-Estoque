<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Total Products
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();

        // Sales Metrics
        $thisMonthSales = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $lastMonthSales = Sale::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $salesGrowth = $lastMonthSales > 0 
            ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100 
            : 0;

        // Top Products
        $topProducts = DB::table('sale_items')
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as total_amount')
            )
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Top Categories
        $topCategories = DB::table('sale_items')
            ->select(
                'categories.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as total_amount')
            )
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Top Customers
        $topCustomers = DB::table('sales')
            ->select(
                'customers.name',
                DB::raw('COUNT(sales.id) as total_purchases'),
                DB::raw('SUM(sales.total_amount) as total_spent')
            )
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->whereNotNull('sales.customer_id')
            ->groupBy('customers.id', 'customers.name')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get();

        // Low Stock Products (Critical)
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->select('name', 'sku', 'stock_quantity')
            ->get();

        // Low Stock Products (Near Limit)
        $nearLimitProducts = Product::where('stock_quantity', '>', 5)
            ->where('stock_quantity', '<=', 20)
            ->select('name', 'sku', 'stock_quantity')
            ->get();

        // Sales Chart Data
        $salesChart = DB::table('sales')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCustomers',
            'thisMonthSales',
            'salesGrowth',
            'topProducts',
            'topCategories',
            'topCustomers',
            'lowStockProducts',
            'nearLimitProducts',
            'salesChart'
        ));
    }
}
