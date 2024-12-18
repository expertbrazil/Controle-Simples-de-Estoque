<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;
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

    public function create()
    {
        $categories = Category::where('status', true)->orderBy('name')->get();
        $brands = Brand::where('status', true)->orderBy('name')->get();
        $suppliers = Supplier::where('status', true)->orderBy('nome')->get();

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
                'active' => $request->has('active')
            ]);

            // Remove formatação dos valores monetários
            $moneyFields = ['last_purchase_price', 'freight_cost', 'consumer_price', 'distributor_price'];
            foreach ($moneyFields as $field) {
                if (isset($productData[$field])) {
                    $productData[$field] = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $productData[$field]));
                }
            }

            // Remove formatação dos percentuais
            $percentageFields = ['tax_percentage', 'consumer_markup', 'distributor_markup'];
            foreach ($percentageFields as $field) {
                if (isset($productData[$field])) {
                    $productData[$field] = floatval(str_replace(['%', ','], ['', '.'], $productData[$field]));
                }
            }

            // Remove formatação do peso
            if (isset($productData['weight_kg'])) {
                $productData['weight_kg'] = floatval(str_replace(['kg', ','], ['', '.'], $productData['weight_kg']));
            }

            DB::beginTransaction();

            // Cria o produto
            $product = Product::create($productData);

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
        $categories = Category::where('status', true)->orderBy('name')->get();
        $brands = Brand::where('status', true)->orderBy('name')->get();
        $suppliers = Supplier::where('status', true)->orderBy('nome')->get();

        // Formata os valores monetários
        $product->last_purchase_price = 'R$ ' . number_format($product->last_purchase_price, 2, ',', '.');
        $product->freight_cost = 'R$ ' . number_format($product->freight_cost, 2, ',', '.');
        $product->consumer_price = 'R$ ' . number_format($product->consumer_price, 2, ',', '.');
        $product->distributor_price = 'R$ ' . number_format($product->distributor_price, 2, ',', '.');
        
        // Formata os percentuais
        $product->tax_percentage = number_format($product->tax_percentage, 2, ',', '.') . '%';
        $product->consumer_markup = number_format($product->consumer_markup, 2, ',', '.') . '%';
        $product->distributor_markup = number_format($product->distributor_markup, 2, ',', '.') . '%';
        
        // Formata o peso
        $product->weight_kg = number_format($product->weight_kg, 2, ',', '.') . ' kg';

        return view('products.edit', compact('product', 'categories', 'brands', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $rules = ProductValidation::rules();
        
        // Ajusta a regra unique do SKU para ignorar o produto atual
        $rules['sku'] = 'required|string|max:50|unique:products,sku,' . $product->id;

        $validatedData = $request->validate($rules, ProductValidation::messages());

        try {
            // Calcula os preços
            $prices = $this->calculatePrices($validatedData);

            // Prepara os dados para salvar
            $productData = array_merge($validatedData, [
                'unit_cost' => $prices['unit_cost'],
                'consumer_price' => $prices['consumer_price'],
                'distributor_price' => $prices['distributor_price'],
                'image' => $validatedData['stored_image'] ?? $product->image,
                'active' => $request->has('active')
            ]);

            // Remove formatação dos valores monetários
            $moneyFields = ['last_purchase_price', 'freight_cost', 'consumer_price', 'distributor_price'];
            foreach ($moneyFields as $field) {
                if (isset($productData[$field])) {
                    $productData[$field] = floatval(str_replace(['R$', '.', ','], ['', '', '.'], $productData[$field]));
                }
            }

            // Remove formatação dos percentuais
            $percentageFields = ['tax_percentage', 'consumer_markup', 'distributor_markup'];
            foreach ($percentageFields as $field) {
                if (isset($productData[$field])) {
                    $productData[$field] = floatval(str_replace(['%', ','], ['', '.'], $productData[$field]));
                }
            }

            // Remove formatação do peso
            if (isset($productData['weight_kg'])) {
                $productData['weight_kg'] = floatval(str_replace(['kg', ','], ['', '.'], $productData['weight_kg']));
            }

            DB::beginTransaction();

            // Atualiza o produto
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
}
