<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Estatísticas Gerais
        $totalProducts = Product::count();
        $totalCustomers = Customer::count();

        // Vendas do mês atual
        $now = Carbon::now();
        $thisMonthSales = Sale::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('total_amount');

        // Vendas do mês anterior para comparação
        $lastMonthSales = Sale::whereYear('created_at', $now->copy()->subMonth()->year)
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->sum('total_amount');

        // Calcula o crescimento das vendas
        $salesGrowth = $lastMonthSales > 0 
            ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100 
            : 0;

        // Produtos com estoque baixo (crítico)
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity')
            ->get();

        // Produtos próximos do limite
        $nearLimitProducts = Product::whereBetween('stock_quantity', [6, 20])
            ->orderBy('stock_quantity')
            ->get();

        // Produtos mais vendidos
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total_price) as total_amount')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Categorias mais vendidas
        $topCategories = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total_price) as total_amount')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Clientes mais frequentes
        $topCustomers = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select(
                'customers.name',
                DB::raw('COUNT(*) as total_purchases'),
                DB::raw('SUM(sales.total_amount) as total_spent')
            )
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // Dados para o gráfico de vendas
        $salesChart = Sale::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereBetween('created_at', [$now->copy()->subDays(30), $now])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($sale) {
                return [
                    'date' => Carbon::parse($sale->date)->format('d/m'),
                    'total' => $sale->total
                ];
            });

        return view('dashboard', compact(
            'totalProducts',
            'totalCustomers',
            'thisMonthSales',
            'salesGrowth',
            'lowStockProducts',
            'nearLimitProducts',
            'topProducts',
            'topCategories',
            'topCustomers',
            'salesChart'
        ));
    }
}
