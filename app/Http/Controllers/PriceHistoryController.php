<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriceHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = PriceHistory::with(['product', 'user', 'entry']);

        // Filtrar por produto
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filtrar por período
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filtrar por variação significativa
        if ($request->has('significant_changes')) {
            $threshold = $request->get('threshold', 10);
            $query->significantChanges($threshold);
        }

        $histories = $query->orderBy('created_at', 'desc')->paginate(15);
        $products = Product::orderBy('name')->get();

        return view('price-histories.index', compact('histories', 'products'));
    }

    public function show(Product $product)
    {
        $histories = PriceHistory::forProduct($product->id)
            ->with(['user', 'entry'])
            ->get();

        // Preparar dados para o gráfico
        $chartData = $histories->map(function ($history) {
            return [
                'date' => $history->created_at->format('Y-m-d'),
                'purchase_price' => $history->purchase_price,
                'unit_cost' => $history->unit_cost,
                'consumer_price' => $history->consumer_price,
                'distributor_price' => $history->distributor_price
            ];
        })->values();

        return view('price-histories.show', compact('product', 'histories', 'chartData'));
    }

    public function analysis()
    {
        // Variações significativas no último mês
        $significantChanges = PriceHistory::with('product')
            ->lastMonth()
            ->significantChanges(10)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Produtos com maior aumento percentual no último mês
        $topPriceIncreases = PriceHistory::with('product')
            ->lastMonth()
            ->latestByProduct()
            ->orderByDesc('price_increase')
            ->take(10)
            ->get()
            ->map(function ($history) {
                return [
                    'name' => $history->product->name,
                    'old_price' => $history->old_price,
                    'new_price' => $history->consumer_price,
                    'price_increase' => $history->price_increase
                ];
            });

        return view('price-histories.analysis', compact('significantChanges', 'topPriceIncreases'));
    }
}
