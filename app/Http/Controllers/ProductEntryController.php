<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'purchase_price' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Criar a entrada (o modelo já vai converter o preço corretamente)
            $productEntry = new ProductEntry();
            $productEntry->product_id = $validated['product_id'];
            $productEntry->purchase_price = $this->formatPriceToUnit($validated['purchase_price'], $validated['quantity']);
            $productEntry->quantity = $validated['quantity'];
            $productEntry->notes = $validated['notes'];
            $productEntry->user_id = auth()->id();
            $productEntry->save();

            // Atualizar o estoque do produto
            $product = Product::findOrFail($validated['product_id']);
            $product->stock_quantity += $validated['quantity'];
            
            // Atualizar o preço da última compra com o valor unitário
            $product->last_purchase_price = $productEntry->purchase_price;
            $product->last_purchase_date = now();
            $product->save();

            DB::commit();
            return redirect()
                ->route('product-entries.show', $productEntry)
                ->with('success', 'Entrada cadastrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar entrada: ' . $e->getMessage());
        }
    }

    public function show(ProductEntry $productEntry)
    {
        $productEntry->load(['product', 'user']);
        return view('product-entries.show', compact('productEntry'));
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
            $productEntry->purchase_price = $this->formatPriceToUnit($validated['purchase_price'], $validated['quantity']);
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

    private function formatPriceToUnit($price, $quantity)
    {
        // Remove todos os pontos e substitui vírgula por ponto
        $price = str_replace(['.', ','], ['', '.'], $price);
        
        // Se o valor for maior que 100, provavelmente está em centavos
        if ($price > 100) {
            $price = $price / 100;
        }

        // Calcula o preço unitário
        return $price / $quantity;
    }
}
