<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdvController extends Controller
{
    public function index()
    {
        $products = Product::where('status', true)->get();
        $paymentMethods = PaymentMethod::all();
        
        return view('pdv.index', compact('products', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::findOrFail($request->customer_id);

            // Criar o pedido
            $order = Order::create([
                'customer_id' => $customer->id,
                'payment_method_id' => $request->payment_method_id,
                'discount' => $request->discount ?? 0,
                'price_type' => $request->price_type,
                'status' => 'completed',
                'user_id' => auth()->id()
            ]);

            // Adicionar os itens
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            DB::commit();

            // Redirecionar para a página do recibo
            return redirect()->route('pdv.show', ['order' => $order->id])
                           ->with('success', 'Venda finalizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Erro ao finalizar venda: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Order $order)
    {
        return view('pdv.receipt', compact('order'));
    }

    public function searchCustomer(Request $request)
    {
        try {
            $search = $request->get('q');
            
            $customers = Customer::where('status', 1)
                ->where(function($query) use ($search) {
                    $query->where('nome', 'like', "%{$search}%")
                        ->orWhere('razao_social', 'like', "%{$search}%")
                        ->orWhere('documento', 'like', "%{$search}%");
                })
                ->limit(10)
                ->get();

            $results = $customers->map(function($customer) {
                $text = $customer->tipo_pessoa === 'J' 
                    ? "{$customer->razao_social} - {$customer->documento}"
                    : "{$customer->nome} - {$customer->documento}";
                    
                return [
                    'id' => $customer->id,
                    'text' => $text
                ];
            });

            return response()->json($results);
        } catch (\Exception $e) {
            \Log::error('Erro na busca de clientes: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
