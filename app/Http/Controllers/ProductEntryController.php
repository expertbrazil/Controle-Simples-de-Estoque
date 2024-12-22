<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductEntry;
use App\Models\PriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductEntryController extends Controller
{
    public function index()
    {
        $entries = ProductEntry::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('product-entries.index', compact('entries'));
    }

    public function create()
    {
        return view('product-entries.create');
    }

    protected function validateEntry($data)
    {
        $purchasePrice = $this->formatMonetaryValue($data['purchase_price']);
        $lastPurchasePrice = null;
        
        // Buscar última entrada do produto
        $product = Product::with('lastEntry')->find($data['product_id']);
        if ($product && $product->lastEntry) {
            $lastPurchasePrice = $product->lastEntry->purchase_price;
        }

        // Validações básicas
        if ($purchasePrice <= 0) {
            throw new \Exception('O preço de compra deve ser maior que zero');
        }

        if ($data['quantity'] <= 0) {
            throw new \Exception('A quantidade deve ser maior que zero');
        }

        if ($this->formatMonetaryValue($data['weight_kg'], 3) <= 0) {
            throw new \Exception('O peso deve ser maior que zero');
        }

        // Validar variação significativa de preço (mais de 50%)
        if ($lastPurchasePrice && $purchasePrice > $lastPurchasePrice * 1.5) {
            throw new \Exception(
                'O preço de compra aumentou mais de 50% em relação à última compra. ' .
                'Último preço: R$ ' . number_format($lastPurchasePrice, 2, ',', '.') .
                ' - Novo preço: R$ ' . number_format($purchasePrice, 2, ',', '.')
            );
        }

        // Validar estoque máximo
        $newStock = $product->stock_quantity + $data['quantity'];
        if ($product->max_stock > 0 && $newStock > $product->max_stock) {
            throw new \Exception(
                'A quantidade excede o estoque máximo permitido. ' .
                'Máximo: ' . $product->max_stock .
                ' - Atual: ' . $product->stock_quantity .
                ' - Entrada: ' . $data['quantity']
            );
        }

        return true;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            foreach ($request->entries as $entry) {
                // Validar entrada
                $this->validateEntry($entry);
                
                $product = Product::findOrFail($entry['product_id']);
                
                // Criar a entrada
                $productEntry = ProductEntry::create([
                    'product_id' => $entry['product_id'],
                    'purchase_price' => $this->formatMonetaryValue($entry['purchase_price']),
                    'tax_percentage' => $this->formatMonetaryValue($entry['tax_percentage']),
                    'freight_cost' => $this->formatMonetaryValue($entry['freight_cost']),
                    'weight_kg' => $this->formatMonetaryValue($entry['weight_kg'], 3),
                    'unit_cost' => $this->formatMonetaryValue($entry['unit_cost']),
                    'quantity' => $entry['quantity'],
                    'notes' => $entry['notes'] ?? null,
                    'user_id' => auth()->id()
                ]);

                // Atualizar o estoque
                $product->updateStock($entry['quantity'], 'add');

                // Atualizar preços do produto
                $product->update([
                    'last_purchase_price' => $productEntry->purchase_price,
                    'unit_cost' => $productEntry->unit_cost,
                    'consumer_price' => $product->calculateConsumerPrice($productEntry->unit_cost),
                    'distributor_price' => $product->calculateDistributorPrice($productEntry->unit_cost)
                ]);

                // Registrar histórico de preços
                PriceHistory::create([
                    'product_id' => $product->id,
                    'purchase_price' => $productEntry->purchase_price,
                    'unit_cost' => $productEntry->unit_cost,
                    'consumer_price' => $product->consumer_price,
                    'distributor_price' => $product->distributor_price,
                    'consumer_markup' => $product->consumer_markup,
                    'distributor_markup' => $product->distributor_markup,
                    'tax_percentage' => $productEntry->tax_percentage,
                    'freight_cost' => $productEntry->freight_cost,
                    'entry_id' => $productEntry->id,
                    'user_id' => auth()->id()
                ]);

                // Verificar estoque mínimo após entrada
                if ($product->isLowStock()) {
                    // Registrar alerta de estoque baixo
                    \Log::warning('Produto com estoque baixo após entrada:', [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'current_stock' => $product->stock_quantity,
                        'min_stock' => $product->min_stock
                    ]);
                }
            }

            DB::commit();
            
            return redirect()
                ->route('product-entries.index')
                ->with('success', 'Entrada(s) de produto(s) registrada(s) com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao registrar entrada:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao registrar entrada: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $entry = ProductEntry::with(['product', 'user'])->findOrFail($id);
        
        // Calculando os valores para comparação
        $taxAmount = $entry->purchase_price * ($entry->tax_percentage / 100);
        $freightAmount = $entry->freight_cost * $entry->weight_kg;
        $calculatedUnitCost = $entry->purchase_price + $taxAmount + $freightAmount;
        $calculatedTotalCost = $calculatedUnitCost * $entry->quantity;
        
        // Calculando a média ponderada
        $totalQuantity = $entry->product->stock_quantity;
        $totalCostBefore = $entry->product->cost_price * ($totalQuantity - $entry->quantity);
        $newCostTotal = $totalCostBefore + $entry->total_cost;
        $averageCost = $totalQuantity > 0 ? $newCostTotal / $totalQuantity : $entry->unit_cost;
        
        // Calculando novo preço de venda sugerido
        $currentMarkup = $entry->product->price > 0 ? 
            (($entry->product->price - $entry->product->cost_price) / $entry->product->cost_price) * 100 : 
            30; // markup padrão de 30% se não houver preço atual
        
        $suggestedPrice = $averageCost * (1 + ($currentMarkup / 100));
        
        $comparison = [
            'tax_amount' => $taxAmount,
            'freight_amount' => $freightAmount,
            'calculated_unit_cost' => $calculatedUnitCost,
            'calculated_total_cost' => $calculatedTotalCost,
            'unit_cost_difference' => $calculatedUnitCost - $entry->unit_cost,
            'total_cost_difference' => $calculatedTotalCost - $entry->total_cost,
            'current_product' => [
                'cost_price' => $entry->product->cost_price,
                'sale_price' => $entry->product->price,
                'markup' => $currentMarkup
            ],
            'new_values' => [
                'average_cost' => $averageCost,
                'suggested_price' => $suggestedPrice
            ]
        ];

        return view('product-entries.show', compact('entry', 'comparison'));
    }

    public function edit(ProductEntry $productEntry)
    {
        return view('product-entries.edit', compact('productEntry'));
    }

    public function update(Request $request, ProductEntry $productEntry)
    {
        $validated = $request->validate([
            'purchase_price' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Reverter o estoque antigo
            $product = $productEntry->product;
            $product->stock_quantity -= $productEntry->quantity;

            // Atualizar a entrada (o modelo já vai converter o preço corretamente)
            $productEntry->purchase_price = $this->formatMonetaryValue($validated['purchase_price']);
            $productEntry->quantity = $validated['quantity'];
            $productEntry->notes = $validated['notes'];
            $productEntry->save();

            // Atualizar o estoque com a nova quantidade
            $product->stock_quantity += $validated['quantity'];

            // Atualizar o preço da última compra se esta for a entrada mais recente
            $latestEntry = $product->entries()->latest()->first();
            if ($latestEntry && $latestEntry->id === $productEntry->id) {
                $product->last_purchase_price = $productEntry->purchase_price;
                $product->last_purchase_date = $productEntry->created_at;
            }

            $product->save();

            DB::commit();
            return redirect()
                ->route('product-entries.show', $productEntry)
                ->with('success', 'Entrada atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar entrada: ' . $e->getMessage());
        }
    }

    public function destroy(ProductEntry $productEntry)
    {
        DB::beginTransaction();
        try {
            // Atualizar o estoque do produto
            $product = $productEntry->product;
            $product->stock_quantity -= $productEntry->quantity;
            $product->save();

            // Deletar a entrada
            $productEntry->delete();

            DB::commit();
            return redirect()
                ->route('product-entries.index')
                ->with('success', 'Entrada removida com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->route('product-entries.index')
                ->with('error', 'Erro ao remover entrada: ' . $e->getMessage());
        }
    }

    public function getProducts(Request $request)
    {
        $term = $request->get('term');
        $products = Product::where('name', 'like', "%{$term}%")
            ->orWhere('sku', 'like', "%{$term}%")
            ->select('id', 'name', 'sku', 'price')
            ->limit(10)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'value' => $product->name,
                    'label' => "{$product->sku} - {$product->name}",
                    'price' => $product->price
                ];
            });

        return response()->json($products);
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::where('status', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->with(['lastEntry'])
            ->limit(10)
            ->get()
            ->map(function($product) {
                $lastEntry = $product->lastEntry;
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'weight_kg' => $product->weight_kg,
                    'last_entry' => $lastEntry ? [
                        'purchase_price' => number_format($lastEntry->purchase_price, 2, ',', '.'),
                        'tax_percentage' => number_format($lastEntry->tax_percentage, 2, ',', '.'),
                        'freight_cost' => number_format($lastEntry->freight_cost, 2, ',', '.')
                    ] : null
                ];
            });
        
        return response()->json($products);
    }

    protected function formatMonetaryValue($value, $decimals = 2)
    {
        if (is_string($value)) {
            // Remove pontos dos milhares e substitui vírgula por ponto
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }
        return round(floatval($value), $decimals);
    }

    protected function formatPercentageValue($value)
    {
        if (is_string($value)) {
            // Remove pontos dos milhares e substitui vírgula por ponto
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }
        return round(floatval($value), 2);
    }
}
