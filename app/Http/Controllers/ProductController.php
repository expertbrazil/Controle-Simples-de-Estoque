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
        $term = strtolower($request->get('term', ''));
        
        return Product::where(function($query) use ($term) {
            $query->whereRaw('LOWER(name) like ?', ['%' . $term . '%'])
                  ->when($term, function($q) use ($term) {
                      $q->orWhere('sku', 'like', '%' . $term . '%');
                  });
        })->get();
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

    /**
     * Formata um valor monetário para o formato do banco de dados
     */
    private function formatMoneyToDatabase($value)
    {
        if (empty($value)) {
            return '0.00';
        }

        // Remove tudo exceto números, vírgula e ponto
        $value = preg_replace('/[^\d,.]/', '', $value);
        
        // Substitui vírgula por ponto
        $value = str_replace(',', '.', $value);
        
        // Se houver mais de um ponto, mantém apenas o último
        if (substr_count($value, '.') > 1) {
            $parts = explode('.', $value);
            $last = array_pop($parts);
            $value = implode('', $parts) . '.' . $last;
        }
        
        // Garante que é um número válido com 2 casas decimais
        return number_format((float) $value, 2, '.', '');
    }
}
