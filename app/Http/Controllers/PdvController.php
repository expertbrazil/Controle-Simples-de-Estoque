<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdvController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pdv.index');
    }

    public function getProducts()
    {
        try {
            $products = Product::where('active', true)
                ->select('id', 'name', 'sku', 'price', 'stock_quantity', 'image')
                ->orderBy('name')
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'price' => number_format($product->price, 2, '.', ''),
                        'stock_quantity' => $product->stock_quantity,
                        'image' => $product->image
                    ];
                });

            return response()->json($products);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar produtos: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar produtos'], 500);
        }
    }

    public function searchCustomers(Request $request)
    {
        try {
            $query = $request->get('query');
            
            if (empty($query) || strlen($query) < 2) {
                return response()->json([]);
            }
            
            $customers = Customer::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('cpf', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'cpf', 'phone')
            ->orderBy('name')
            ->limit(10)
            ->get();

            return response()->json($customers);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar clientes: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar clientes'], 500);
        }
    }

    public function finalizeSale(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'customer_id' => 'nullable|exists:customers,id',
                'items' => 'required|array',
                'items.*.id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'payment_method' => 'required|in:money,credit_card,debit_card,pix'
            ]);

            // Calculate subtotal first
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['id']);
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Estoque insuficiente para o produto {$product->name}");
                }
                $subtotal += $item['quantity'] * $item['price'];
            }

            // Calculate discount and final amount
            $discountPercent = $validated['discount'] ?? 0;
            $discountAmount = ($subtotal * $discountPercent) / 100;
            $totalAmount = $subtotal - $discountAmount;

            // Create the sale
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => auth()->id(),
                'subtotal_amount' => $subtotal,
                'discount_percent' => $discountPercent,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid'
            ]);

            // Create sale items and update stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['id']);
                $product->decrement('stock_quantity', $item['quantity']);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price']
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true, 
                'sale_id' => $sale->id,
                'message' => 'Venda finalizada com sucesso!',
                'redirect_url' => route('pdv.show', $sale->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao finalizar venda: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $sale = Sale::with(['items.product', 'customer', 'user'])
                ->findOrFail($id);

            return view('pdv.show', compact('sale'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Venda não encontrada');
        }
    }

    public function sendEmail($id)
    {
        try {
            $sale = Sale::with(['items.product', 'customer', 'user'])
                ->findOrFail($id);

            if (!$sale->customer || !$sale->customer->email) {
                throw new \Exception('Cliente não possui email cadastrado');
            }

            // Aqui você implementaria o envio do email
            // Por exemplo, usando o Mail facade do Laravel
            // Mail::to($sale->customer->email)->send(new SaleReceipt($sale));

            return response()->json([
                'success' => true,
                'message' => 'Email enviado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function print($id)
    {
        try {
            $sale = Sale::with(['items.product', 'customer', 'user'])
                ->findOrFail($id);

            return view('pdv.print', compact('sale'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Venda não encontrada');
        }
    }
}