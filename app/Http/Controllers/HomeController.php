<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Totais
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSales = Sale::count();
        $totalValue = Product::sum(DB::raw('price * stock_quantity'));

        // Produtos com estoque baixo
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity')
            ->limit(5)
            ->get();

        // Últimas vendas
        $recentSales = Sale::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($sale) {
                return (object) [
                    'created_at' => $sale->created_at,
                    'customer_name' => $sale->customer ? $sale->customer->name : 'Cliente não identificado',
                    'total' => $sale->total_amount,
                    'status' => 'Concluída'
                ];
            });

        return view('home', compact(
            'totalProducts',
            'totalCategories',
            'totalSales',
            'totalValue',
            'lowStockProducts',
            'recentSales'
        ));
    }
}
