<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;
use App\Mail\SaleDetails;

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
        try {
            DB::beginTransaction();

            // Validar os dados
            $validatedData = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'discount_percent' => 'nullable|numeric|min:0|max:100'
            ]);

            // Criar a venda
            $sale = Sale::create([
                'customer_id' => $validatedData['customer_id'],
                'discount_percent' => $validatedData['discount_percent'] ?? 0,
                'status' => 'pending'
            ]);

            $totalAmount = 0;

            // Adicionar os itens
            foreach ($validatedData['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Verificar estoque
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Estoque insuficiente para o produto {$product->name}");
                }

                // Criar o item da venda
                $saleItem = $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Atualizar o estoque
                $product->decrement('stock_quantity', $item['quantity']);

                $totalAmount += $item['quantity'] * $item['price'];
            }

            // Calcular o desconto
            $discount = $totalAmount * ($validatedData['discount_percent'] ?? 0) / 100;
            $finalAmount = $totalAmount - $discount;

            // Atualizar os totais da venda
            $sale->update([
                'subtotal_amount' => $totalAmount,
                'discount_amount' => $discount,
                'total_amount' => $finalAmount
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venda criada com sucesso!',
                'sale' => $sale->load(['customer', 'items.product'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao criar venda: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar a venda: ' . $e->getMessage()
            ], 422);
        }
    }

    public function hold(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não está autenticado'
            ], 401);
        }

        DB::beginTransaction();
        try {
            $sale = Sale::create([
                'customer_id' => $request->customer_id,
                'status' => 'held',
                'user_id' => auth()->id(),
            ]);

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Estoque insuficiente para o produto: {$product->name}");
                }

                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price']
                ]);

                $product->decrement('stock_quantity', $item['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venda segurada com sucesso!',
                'sale' => $sale->load(['customer', 'items.product']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao segurar a venda: ' . $e->getMessage()
            ], 422);
        }
    }

    public function getHeldSales()
    {
        $sales = Sale::with(['customer', 'items.product'])
            ->where('status', 'held')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($sale) {
                return [
                    'id' => $sale->id,
                    'customer' => $sale->customer ? $sale->customer->name : 'Cliente não identificado',
                    'items' => $sale->items->map(function($item) {
                        return [
                            'product_id' => $item->product_id,
                            'name' => $item->product->name,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'total' => $item->total_price
                        ];
                    }),
                    'created_at' => $sale->created_at->format('d/m/Y H:i')
                ];
            });

        return response()->json($sales);
    }

    public function finalizeSale(Request $request, Sale $sale)
    {
        if ($sale->status !== 'held') {
            return response()->json([
                'success' => false,
                'message' => 'Venda não está segurada'
            ], 422);
        }

        $request->validate([
            'payment_method' => 'required|in:money,credit_card,debit_card,pix',
            'paid_amount' => 'required|numeric|min:0',
            'installments' => 'nullable|integer|min:1|max:12',
            'payment_details' => 'nullable|array',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $sale->update([
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount,
                'installments' => $request->installments,
                'payment_details' => $request->payment_details,
                'notes' => $request->notes,
                'status' => 'completed'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venda finalizada com sucesso!',
                'sale' => $sale->load(['customer', 'items.product']),
                'receipt_url' => route('sales.receipt', $sale->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao finalizar a venda: ' . $e->getMessage()
            ], 422);
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);
        $whatsappMessage = $this->generateWhatsAppMessage($sale);
        return view('sales.show', compact('sale', 'whatsappMessage'));
    }

    public function generateWhatsAppMessage(Sale $sale)
    {
        $message = "*Pedido #" . str_pad($sale->id, 6, '0', STR_PAD_LEFT) . "*\n\n";
        $message .= "Data: " . $sale->created_at->format('d/m/Y') . "\n";
        $message .= "Total: R$ " . number_format($sale->total_amount, 2, ',', '.') . "\n\n";
        
        $message .= "*Itens:*\n";
        foreach ($sale->items as $item) {
            $subtotal = $item->quantity * $item->price;
            $message .= "• {$item->product->name}\n";
            $message .= "  {$item->quantity}x R$ " . number_format($item->price, 2, ',', '.') . 
                       " = R$ " . number_format($subtotal, 2, ',', '.') . "\n";
        }

        $message .= "\n*Total: R$ " . number_format($sale->total_amount, 2, ',', '.') . "*";

        return $message;
    }

    public function searchCustomers(Request $request)
    {
        $term = $request->get('term');
        
        $customers = Customer::where(function($query) use ($term) {
            $query->where('name', 'LIKE', "%{$term}%")
                  ->orWhere('phone', 'LIKE', "%{$term}%")
                  ->orWhere('cpf', 'LIKE', "%{$term}%")
                  ->orWhere('email', 'LIKE', "%{$term}%");
        })
        ->select('id', 'name', 'phone', 'email', 'cpf')
        ->take(10)
        ->get()
        ->map(function($customer) {
            $label = $customer->name;
            if ($customer->phone) {
                $label .= " - " . $customer->phone;
            }
            if ($customer->cpf) {
                $label .= " (CPF: " . $customer->cpf . ")";
            }
            
            return [
                'id' => $customer->id,
                'label' => $label,
                'value' => $customer->name,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'cpf' => $customer->cpf
            ];
        });

        return response()->json($customers);
    }

    public function searchProducts(Request $request)
    {
        try {
            $term = $request->get('term');
            
            $products = Product::where('active', true)
                ->where('stock_quantity', '>', 0)
                ->where(function($query) use ($term) {
                    $query->where('name', 'LIKE', "%{$term}%")
                          ->orWhere('sku', 'LIKE', "%{$term}%");
                })
                ->select('id', 'name', 'sku', 'price', 'stock_quantity', 'image')
                ->take(10)
                ->get();

            $formattedProducts = $products->map(function($product) {
                // Se a imagem existe, usa ela, senão usa a imagem padrão
                $imageUrl = $product->image 
                    ? asset('imagens/produtos/' . $product->image) 
                    : asset('images/no-image.png');

                return [
                    'id' => $product->id,
                    'label' => "{$product->name} (SKU: {$product->sku})",
                    'value' => $product->name,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => number_format($product->price, 2, '.', ''),
                    'stock' => $product->stock_quantity,
                    'image_url' => $imageUrl
                ];
            });

            return response()->json($formattedProducts);
            
        } catch (\Exception $e) {
            \Log::error('Erro na busca de produtos: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'error' => 'Erro ao buscar produtos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function listProducts(Request $request)
    {
        try {
            $query = $request->query('query');
            $productsQuery = Product::where('active', true)
                ->where('stock_quantity', '>', 0);
            
            if ($query) {
                $productsQuery->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('sku', 'like', "%{$query}%")
                      ->orWhere('barcode', 'like', "%{$query}%");
                });
            }
            
            $products = $productsQuery->select('id', 'name', 'sku', 'price', 'stock_quantity as stock', 'image')
                ->get()
                ->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'price' => number_format($product->price, 2, '.', ''),
                        'stock' => $product->stock,
                        'image_url' => $product->image 
                            ? asset('imagens/produtos/' . $product->image)
                            : asset('images/no-image.png')
                    ];
                });

            return response()->json($products);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar produtos: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao listar produtos'], 500);
        }
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

    public function sendEmail(Request $request, Sale $sale)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'message' => 'nullable|string'
            ]);

            Mail::to($request->email)
                ->send(new SaleDetails($sale, $request->message));

            return response()->json([
                'success' => true,
                'message' => 'Email enviado com sucesso!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['customer', 'items.product', 'user']);
        return view('sales.receipt', compact('sale'));
    }

    public function cancel(Sale $sale)
    {
        if ($sale->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Esta venda já está cancelada'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Restaurar estoque
            foreach ($sale->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }

            $sale->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venda cancelada com sucesso!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cancelar a venda: ' . $e->getMessage()
            ], 422);
        }
    }
}
