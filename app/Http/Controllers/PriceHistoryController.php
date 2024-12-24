<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $significantChanges = PriceHistory::with(['product', 'user'])
            ->lastMonth()
            ->significantChanges(10)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($history) {
                return [
                    'product_name' => $history->product->name,
                    'product_sku' => $history->product->sku,
                    'product_image' => $history->product->image,
                    'old_price' => $history->old_price,
                    'new_price' => $history->consumer_price,
                    'variation' => $history->price_variation,
                    'formatted_variation' => $history->formatted_price_variation,
                    'formatted_old_price' => 'R$ ' . number_format($history->old_price, 2, ',', '.'),
                    'formatted_new_price' => 'R$ ' . number_format($history->consumer_price, 2, ',', '.'),
                    'created_at' => $history->created_at,
                    'price_increase' => $history->price_variation
                ];
            });

        // Produtos com maior aumento percentual no último mês
        $topPriceIncreases = PriceHistory::with(['product', 'user'])
            ->lastMonth()
            ->latestByProduct()
            ->get()
            ->sortByDesc('price_variation')
            ->take(10)
            ->values()
            ->map(function ($history) {
                return [
                    'product_name' => $history->product->name,
                    'product_sku' => $history->product->sku,
                    'product_image' => $history->product->image,
                    'old_price' => $history->old_price,
                    'new_price' => $history->consumer_price,
                    'variation' => $history->price_variation,
                    'formatted_variation' => $history->formatted_price_variation,
                    'formatted_old_price' => 'R$ ' . number_format($history->old_price, 2, ',', '.'),
                    'formatted_new_price' => 'R$ ' . number_format($history->consumer_price, 2, ',', '.'),
                    'created_at' => $history->created_at,
                    'price_increase' => $history->price_variation
                ];
            });

        // Preparar dados para os gráficos e contadores
        $allProducts = \App\Models\Product::all();
        $productsWithChanges = PriceHistory::with('product')
            ->lastMonth()
            ->select('product_id')
            ->distinct()
            ->get()
            ->pluck('product_id');

        $priceChangesData = [
            'increases' => [],
            'decreases' => [],
            'increasesCount' => 0,
            'decreasesCount' => 0,
            'noChangeCount' => $allProducts->count() - $productsWithChanges->count() // Produtos sem alteração
        ];

        // Analisar variações de preço
        $latestChanges = PriceHistory::with('product')
            ->lastMonth()
            ->whereIn('id', function($query) {
                $query->select(\DB::raw('MAX(id)'))
                    ->from('price_histories')
                    ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)')
                    ->groupBy('product_id');
            })
            ->get();

        foreach ($latestChanges as $change) {
            $variation = $change->price_variation;
            
            if ($variation > 0) {
                $priceChangesData['increasesCount']++;
                $priceChangesData['increases'][] = [
                    'product' => $change->product->name,
                    'variation' => $variation
                ];
            } elseif ($variation < 0) {
                $priceChangesData['decreasesCount']++;
                $priceChangesData['decreases'][] = [
                    'product' => $change->product->name,
                    'variation' => abs($variation)
                ];
            }
        }

        // Ordenar por variação e limitar a 5 itens
        usort($priceChangesData['increases'], function($a, $b) {
            return $b['variation'] <=> $a['variation'];
        });
        usort($priceChangesData['decreases'], function($a, $b) {
            return $b['variation'] <=> $a['variation'];
        });

        $priceChangesData['increases'] = array_slice($priceChangesData['increases'], 0, 5);
        $priceChangesData['decreases'] = array_slice($priceChangesData['decreases'], 0, 5);

        return view('price-histories.analysis', compact(
            'significantChanges',
            'topPriceIncreases',
            'priceChangesData'
        ));
    }
}
