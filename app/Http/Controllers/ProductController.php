<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\PriceHistory;
use App\Rules\ProductValidation;
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

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'supplier']);

        // Filtro por nome/SKU/código de barras
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $products = $query->paginate(10)->withQueryString();

        // Carregar dados para os filtros sem nenhuma validação
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $suppliers = Supplier::orderBy('nome')->get();

        $products->getCollection()->transform(function ($product) {
            $product->formatted_consumer_price = 'R$ ' . number_format($product->consumer_price, 2, ',', '.');
            $product->formatted_distributor_price = 'R$ ' . number_format($product->distributor_price, 2, ',', '.');
            $product->image_url = $product->image 
                ? "/images/produtos/{$product->image}" 
                : "/images/nova_rosa_callback_ok.webp";
            return $product;
        });
        
        return view('products.index', compact('products', 'categories', 'brands', 'suppliers'));
    }

    public function create()
    {
        // Carrega os dados disponíveis, mesmo que vazios
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::where('status', true)
            ->orderBy('nome')
            ->get()
            ->map(function($supplier) {
                // Se for pessoa jurídica, usa razão social, senão usa nome
                $supplier->nome_display = $supplier->tipo_pessoa === 'J' 
                    ? $supplier->razao_social 
                    : $supplier->nome;
                return $supplier;
            });

        return view('products.create', compact('categories', 'brands', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            ProductValidation::rules(),
            ProductValidation::messages()
        );

        try {
            // Calcula os preços
            $prices = $this->calculatePrices($validatedData);

            // Prepara os dados para salvar
            $productData = array_merge($validatedData, [
                'unit_cost' => $prices['unit_cost'],
                'consumer_price' => $prices['consumer_price'],
                'distributor_price' => $prices['distributor_price'],
                'image' => $validatedData['stored_image'] ?? null,
                'status' => $request->has('status')
            ]);

            // Remove formatação dos valores monetários e percentuais
            $this->formatMonetaryAndPercentageFields($productData);

            DB::beginTransaction();

            // Cria o produto
            $product = Product::create($productData);

            // Registra o histórico inicial de preços
            $this->updatePrices($product, $productData, 'Cadastro inicial do produto');

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'Produto cadastrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar produto: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar produto. Por favor, tente novamente.');
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::where('status', true)
            ->orderBy('nome')
            ->get()
            ->map(function($supplier) {
                // Se for pessoa jurídica, usa razão social, senão usa nome
                $supplier->nome_display = $supplier->tipo_pessoa === 'J' 
                    ? $supplier->razao_social 
                    : $supplier->nome;
                return $supplier;
            });

        return view('products.edit', compact('product', 'categories', 'brands', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate(
            ProductValidation::rules($product->id),
            ProductValidation::messages()
        );

        try {
            // Calcula os preços
            $prices = $this->calculatePrices($validatedData);

            // Prepara os dados para salvar
            $productData = array_merge($validatedData, [
                'unit_cost' => $prices['unit_cost'],
                'consumer_price' => $prices['consumer_price'],
                'distributor_price' => $prices['distributor_price'],
                'status' => $request->has('status')
            ]);

            // Remove formatação dos valores monetários e percentuais
            $this->formatMonetaryAndPercentageFields($productData);

            DB::beginTransaction();

            // Atualiza o produto
            $product->update($productData);

            // Registra o histórico de preços
            $this->updatePrices($product, $productData, 'Atualização do produto');

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

    protected function updatePrices(Product $product, array $data, string $reason = null)
    {
        $oldPrices = [
            'last_purchase_price' => $product->last_purchase_price,
            'unit_cost' => $product->unit_cost,
            'consumer_price' => $product->consumer_price,
            'distributor_price' => $product->distributor_price,
        ];

        $newPrices = [
            'last_purchase_price' => $data['last_purchase_price'] ?? $product->last_purchase_price,
            'unit_cost' => $data['unit_cost'] ?? $product->unit_cost,
            'consumer_price' => $data['consumer_price'] ?? $product->consumer_price,
            'distributor_price' => $data['distributor_price'] ?? $product->distributor_price,
        ];

        // Verifica se houve alteração em algum preço
        $pricesChanged = false;
        foreach ($newPrices as $field => $value) {
            if (bccomp($value, $oldPrices[$field], 2) !== 0) {
                $pricesChanged = true;
                break;
            }
        }

        // Se houve alteração, registra no histórico
        if ($pricesChanged) {
            PriceHistory::create([
                'product_id' => $product->id,
                'last_purchase_price' => $newPrices['last_purchase_price'],
                'unit_cost' => $newPrices['unit_cost'],
                'consumer_price' => $newPrices['consumer_price'],
                'distributor_price' => $newPrices['distributor_price'],
                'change_reason' => $reason,
                'user_id' => auth()->id()
            ]);
        }

        return $newPrices;
    }

    protected function calculatePrices($data)
    {
        // Remove formatação do preço de compra
        $lastPurchasePrice = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $data['last_purchase_price']));
        
        // Remove formatação do percentual de impostos
        $taxPercentage = floatval(str_replace(['%', ','], ['', '.'], $data['tax_percentage']));
        
        // Remove formatação do custo de frete
        $freightCost = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $data['freight_cost']));
        
        // Calcula o custo unitário
        $unitCost = $lastPurchasePrice + ($lastPurchasePrice * ($taxPercentage / 100)) + $freightCost;
        
        // Remove formatação das margens
        $consumerMarkup = floatval(str_replace(['%', ','], ['', '.'], $data['consumer_markup']));
        $distributorMarkup = floatval(str_replace(['%', ','], ['', '.'], $data['distributor_markup']));
        
        // Calcula os preços de venda
        $consumerPrice = $unitCost * (1 + ($consumerMarkup / 100));
        $distributorPrice = $unitCost * (1 + ($distributorMarkup / 100));
        
        return [
            'unit_cost' => $unitCost,
            'consumer_price' => $consumerPrice,
            'distributor_price' => $distributorPrice
        ];
    }

    protected function formatMonetaryAndPercentageFields(array &$data)
    {
        // Remove formatação dos valores monetários
        $moneyFields = ['last_purchase_price', 'freight_cost', 'consumer_price', 'distributor_price'];
        foreach ($moneyFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $data[$field]));
            }
        }

        // Remove formatação dos percentuais
        $percentageFields = ['tax_percentage', 'consumer_markup', 'distributor_markup'];
        foreach ($percentageFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = floatval(str_replace(['%', ','], ['', '.'], $data[$field]));
            }
        }

        // Remove formatação do peso
        if (isset($data['weight_kg'])) {
            $data['weight_kg'] = floatval(str_replace(['kg', ','], ['', '.'], $data['weight_kg']));
        }
    }
}
