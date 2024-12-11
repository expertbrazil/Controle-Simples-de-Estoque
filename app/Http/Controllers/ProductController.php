<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $products = Product::with(['category', 'brand'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $products->getCollection()->transform(function ($product) {
            $product->formatted_consumer_price = 'R$ ' . number_format($product->consumer_price, 2, ',', '.');
            $product->formatted_distributor_price = 'R$ ' . number_format($product->distributor_price, 2, ',', '.');
            $product->image_url = $product->image 
                ? "/images/produtos/{$product->image}" 
                : "/images/nova_rosa_callback_ok.webp";
            return $product;
        });
        
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        $brands = Brand::where('active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('active', true)->orderBy('name')->get();

        return view('products.create', compact('categories', 'brands', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:100|unique:products',
            'barcode' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'last_purchase_price' => 'required|string',
            'tax_percentage' => 'required|numeric|min:0',
            'freight_cost' => 'required|string',
            'weight_kg' => 'required|numeric|min:0',
            'consumer_markup' => 'required|numeric|min:0',
            'distributor_markup' => 'required|numeric|min:0',
            'consumer_price' => 'required|string',
            'distributor_price' => 'required|string',
            'stored_image' => 'nullable|string',
            'active' => 'boolean'
        ]);

        try {
            // Calcula os preços
            $prices = $this->calculatePrices($validatedData);

            // Prepara os dados para salvar
            $productData = array_merge($validatedData, [
                'unit_cost' => $prices['unit_cost'],
                'consumer_price' => $prices['consumer_price'],
                'distributor_price' => $prices['distributor_price'],
                'image' => $validatedData['stored_image'] ?? null,
                'active' => $request->has('active')
            ]);

            // Remove formatação dos valores monetários
            $moneyFields = ['last_purchase_price', 'freight_cost', 'consumer_price', 'distributor_price'];
            foreach ($moneyFields as $field) {
                $productData[$field] = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $productData[$field]));
            }

            DB::beginTransaction();

            $product = Product::create($productData);

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'Produto cadastrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao cadastrar produto: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar produto. Por favor, tente novamente.');
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::where('active', true)->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0',
            'last_purchase_price' => 'required|string',
            'tax_percentage' => 'required|numeric|min:0',
            'freight_cost' => 'required|string',
            'weight_kg' => 'required|numeric|min:0',
            'consumer_markup' => 'required|numeric|min:0',
            'distributor_markup' => 'required|numeric|min:0',
            'consumer_price' => 'required|string',
            'distributor_price' => 'required|string',
            'stored_image' => 'nullable|string',
            'active' => 'boolean'
        ]);

        try {
            // Calcula os preços
            $prices = $this->calculatePrices($validatedData);

            // Prepara os dados para salvar
            $productData = array_merge($validatedData, [
                'unit_cost' => $prices['unit_cost'],
                'consumer_price' => $prices['consumer_price'],
                'distributor_price' => $prices['distributor_price'],
                'active' => $request->has('active')
            ]);

            // Atualizar imagem apenas se uma nova foi enviada
            if (!empty($validatedData['stored_image'])) {
                if ($validatedData['stored_image'] !== $product->image) {
                    if ($product->image) {
                        $this->imageService->delete($product->image, 'products');
                    }
                    $productData['image'] = $validatedData['stored_image'];
                }
            }

            // Remove formatação dos valores monetários
            $moneyFields = ['last_purchase_price', 'freight_cost', 'consumer_price', 'distributor_price'];
            foreach ($moneyFields as $field) {
                $productData[$field] = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $productData[$field]));
            }

            DB::beginTransaction();

            $product->update($productData);

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'Produto atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar produto: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar produto. Por favor, tente novamente.');
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->image) {
                $this->imageService->delete($product->image, 'products');
            }
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
                        'image_url' => $product->image ? "/images/produtos/{$product->image}" : null
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
                        'image_url' => $product->image ? "/images/produtos/{$product->image}" : null
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
            $image = $request->file('image');
            $fileName = $this->imageService->store($image, 'produtos');
            
            return response()->json([
                'success' => true,
                'fileName' => $fileName,
                'thumbnail' => "/images/produtos/{$fileName}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer upload da imagem'
            ], 500);
        }
    }

    public function getProducts(Request $request)
    {
        $term = $request->get('term');
        $products = Product::where('name', 'LIKE', "%{$term}%")
            ->orWhere('sku', 'LIKE', "%{$term}%")
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'text' => $product->name,
                    'sku' => $product->sku,
                    'consumer_price' => number_format($product->consumer_price, 2, '.', ''),
                    'distributor_price' => number_format($product->distributor_price, 2, '.', ''),
                    'stock_quantity' => $product->stock_quantity,
                    'image_url' => $product->image 
                        ? "/images/produtos/{$product->image}"
                        : "/images/produtos/no-image.webp"
                ];
            });

        return response()->json($products);
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

    protected function calculatePrices($data)
    {
        // Calcula o custo unitário
        $purchasePrice = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $data['last_purchase_price']));
        $taxPercentage = floatval($data['tax_percentage']);
        $freightCost = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $data['freight_cost']));
        $weightKg = floatval($data['weight_kg']);

        // Calcula o valor dos impostos
        $taxAmount = $purchasePrice * ($taxPercentage / 100);
        
        // Calcula o custo do frete por unidade
        $freightPerUnit = $weightKg > 0 ? ($freightCost / $weightKg) : 0;
        
        // Calcula o custo total por unidade
        $unitCost = $purchasePrice + $taxAmount + $freightPerUnit;

        // Calcula os preços com base nos markups
        $consumerMarkup = floatval($data['consumer_markup']);
        $distributorMarkup = floatval($data['distributor_markup']);

        return [
            'unit_cost' => $unitCost,
            'consumer_price' => $unitCost * (1 + ($consumerMarkup / 100)),
            'distributor_price' => $unitCost * (1 + ($distributorMarkup / 100))
        ];
    }
}
