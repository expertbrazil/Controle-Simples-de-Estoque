<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

class SaleController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sales = Sale::with(['customer', 'items.product'])->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:money,credit_card,debit_card,pix',
            'notes' => 'nullable|string|max:1000'
        ]);

        // Garantir que temos um usuário autenticado
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não está autenticado'
            ], 401);
        }

        DB::beginTransaction();
        try {
            // Calcular o subtotal
            $subtotal = collect($request->products)->sum(function ($item) {
                return $item['quantity'] * $item['price'];
            });

            // Calcular o desconto
            $discount = 0;
            if ($request->filled('discount_type') && $request->filled('discount_value')) {
                if ($request->discount_type === 'percentage') {
                    $percentage = min(100, $request->discount_value);
                    $discount = ($subtotal * $percentage) / 100;
                } else {
                    $discount = min($subtotal, $request->discount_value);
                }
            }

            // Criar a venda
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'total_amount' => $subtotal - $discount,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'user_id' => auth()->id() // Garantir que temos o ID do usuário
            ]);

            // Adicionar os produtos
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Verificar estoque
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Estoque insuficiente para o produto: {$product->name}");
                }

                // Criar o item da venda
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price']
                ]);

                // Atualizar o estoque
                $product->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venda realizada com sucesso!',
                'sale' => $sale
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar a venda: ' . $e->getMessage()
            ], 422);
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);
        return view('sales.show', compact('sale'));
    }

    public function searchCustomers(Request $request)
    {
        $term = $request->get('term');
        $customers = Customer::where(function($query) use ($term) {
            $query->where('name', 'LIKE', "%{$term}%")
                  ->orWhere('phone', 'LIKE', "%{$term}%");
        })
        ->select('id', 'name', 'phone')
        ->limit(10)
        ->get()
        ->map(function($customer) {
            return [
                'id' => $customer->id,
                'label' => "{$customer->name} - {$customer->phone}",
                'value' => $customer->name
            ];
        });

        return response()->json($customers);
    }

    public function searchProducts(Request $request)
    {
        $term = $request->get('term');
        
        $products = Product::where(function($query) use ($term) {
            $query->where('name', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%");
        })
        ->where('active', true)
        ->where('stock_quantity', '>', 0)
        ->take(10)
        ->get()
        ->map(function($product) {
            return [
                'id' => $product->id,
                'label' => "{$product->name} (SKU: {$product->sku})",
                'value' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock_quantity
            ];
        });
        
        return response()->json($products);
    }

    private function convertBRLToDecimal($value)
    {
        if (is_numeric($value)) {
            return $value;
        }
        
        // Remove R$ se existir
        $value = str_replace('R$', '', $value);
        // Remove pontos de milhar
        $value = str_replace('.', '', $value);
        // Substitui vírgula decimal por ponto
        $value = str_replace(',', '.', $value);
        // Remove espaços
        $value = trim($value);
        
        return (float) $value;
    }
}
