<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class ProductController extends BaseController
{
    protected $imageService;
    
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Buscar todas as categorias ativas com hierarquia
        $categories = Category::with('parent')
            ->where('active', true)
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->parent 
                        ? $category->parent->name . ' > ' . $category->name 
                        : $category->name
                ];
            });

        \Log::info('Categorias no create:', [
            'count' => $categories->count(),
            'categories' => $categories->toArray()
        ]);

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|string',
            'cost_price' => 'required|string',
            'description' => 'nullable|string',
            'stored_image' => 'nullable|string',
            'active' => 'boolean'
        ]);

        try {
            $data = [
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'price' => $this->formatMoneyToDatabase($validated['price']),
                'cost_price' => $this->formatMoneyToDatabase($validated['cost_price']),
                'description' => $validated['description'] ?? null,
                'image' => $validated['stored_image'] ?? null,
                'active' => $request->has('active')
            ];

            Product::create($data);

            return redirect()->route('products.index')
                ->with('success', 'Produto criado com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao criar produto: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Erro ao criar produto: ' . $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::where('active', true)->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|string',
            'cost_price' => 'required|string',
            'description' => 'nullable|string',
            'stored_image' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'active' => 'boolean'
        ]);

        try {
            $data = [
                'name' => $validated['name'],
                'sku' => $validated['sku'],
                'category_id' => $validated['category_id'],
                'price' => $this->formatMoneyToDatabase($validated['price']),
                'cost_price' => $this->formatMoneyToDatabase($validated['cost_price']),
                'description' => $validated['description'] ?? null,
                'stock_quantity' => $validated['stock_quantity'],
                'min_stock' => $validated['min_stock'] ?? 5,
                'active' => $request->has('active')
            ];

            // Atualizar imagem apenas se uma nova foi enviada
            if (!empty($validated['stored_image'])) {
                // Se a imagem for diferente da atual
                if ($validated['stored_image'] !== $product->image) {
                    // Deletar imagem antiga se existir
                    if ($product->image) {
                        $this->imageService->delete($product->image, 'products');
                    }
                    $data['image'] = $validated['stored_image'];
                }
            }

            $product->update($data);

            return redirect()->route('products.index')
                ->with('success', 'Produto atualizado com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar produto: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Erro ao atualizar produto: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            $products = Product::where('active', true)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('sku', 'like', "%{$query}%");
                })
                ->select('id', 'name', 'sku', 'price', 'stock_quantity as stock', 'image')
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'price' => number_format($product->price, 2, '.', ''),
                        'stock' => $product->stock,
                        'image' => $product->image
                    ];
                });

            return response()->json($products);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar produtos: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar produtos'], 500);
        }
    }

    public function getProductsForSale()
    {
        try {
            \Log::info('Iniciando getProductsForSale');
            
            $products = Product::where('active', true)
                ->select('id', 'name', 'sku', 'price', 'stock_quantity as stock', 'image')
                ->orderBy('name')
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'price' => number_format($product->price, 2, '.', ''),
                        'stock' => $product->stock,
                        'image' => $product->image
                    ];
                });
            
            \Log::info('Produtos encontrados:', ['count' => $products->count()]);
            
            return response()->json($products);
        } catch (\Exception $e) {
            \Log::error('Erro em getProductsForSale: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar produtos'], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $fileName = $this->imageService->store($request->file('image'), 'products');
                
                return response()->json([
                    'success' => true,
                    'fileName' => $fileName,
                    'imageUrl' => Storage::url('products/' . $fileName)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Nenhuma imagem foi enviada.'
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Erro ao fazer upload da imagem: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer upload da imagem: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function formatMoneyToDatabase($value)
    {
        if (empty($value)) return 0;
        
        // Remove o símbolo da moeda e espaços
        $value = str_replace(['R$', ' '], '', $value);
        // Substitui vírgula por ponto
        $value = str_replace(',', '.', $value);
        // Converte para float
        return (float) $value;
    }
}
