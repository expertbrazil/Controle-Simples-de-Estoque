<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Supplier;
use App\Models\PriceHistory;
use App\Models\PriceList;
use App\Rules\ProductValidation;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Filtro por categoria
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtro por marca
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Filtro por fornecedor
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }

        // Filtro por status do estoque
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->whereColumn('stock_quantity', '<=', 'min_stock')
                          ->where('stock_quantity', '>', 0);
                    break;
                case 'out':
                    $query->where('stock_quantity', 0);
                    break;
                case 'available':
                    $query->where('stock_quantity', '>', 0);
                    break;
            }
        }

        // Ordenação
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $products = $query->paginate(10)->withQueryString();

        // Carregar dados para os filtros apenas ativos
        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::where('status', true)
            ->whereJsonContains('flag', 'fornecedor')
            ->orderBy('nome')
            ->get();

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
            ->whereJsonContains('flag', 'fornecedor')
            ->orderBy('nome')
            ->get()
            ->map(function($supplier) {
                // Se for pessoa jurídica, usa razão social, senão usa nome
                $supplier->nome_display = $supplier->tipo_pessoa === 'J' 
                    ? $supplier->razao_social 
                    : $supplier->nome;
                return $supplier;
            });

        $distributorPriceLists = PriceList::where('type', 'distributor')->where('is_active', true)->get();
        $consumerPriceLists = PriceList::where('type', 'consumer')->where('is_active', true)->get();

        return view('products.create', compact('categories', 'brands', 'suppliers', 'distributorPriceLists', 'consumerPriceLists'));
    }

    public function store(Request $request)
    {
        try {
            // Log dos dados recebidos
            \Log::info('Dados recebidos:', $request->all());

            // Remove formatação dos valores monetários e percentuais antes da validação
            $data = $request->all();
            $this->formatMonetaryAndPercentageFields($data);

            // Cria uma nova request com os dados formatados
            $formattedRequest = new Request($data);

            // Valida os dados formatados
            $validatedData = $formattedRequest->validate(
                ProductValidation::rules(),
                ProductValidation::messages()
            );

            DB::beginTransaction();

            // Trata a imagem se houver
            if ($request->hasFile('image')) {
                $validatedData['image'] = $this->imageService->store($request->file('image'), 'produtos');
            }

            // Prepara os dados para salvar
            $productData = array_merge($validatedData, [
                'status' => $request->input('status', true)
            ]);

            // Log dos dados finais
            \Log::info('Dados finais para salvar:', $productData);

            // Cria o produto
            $product = Product::create($productData);

            // Registra o histórico inicial de preços
            $this->updatePriceHistory($product, $productData, 'Cadastro inicial do produto');

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'Produto cadastrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao criar produto: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao cadastrar produto: ' . $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        // Formata os valores monetários e percentuais para exibição
        $formattedProduct = [
            'last_purchase_price' => number_format($product->last_purchase_price ?? 0, 2, ',', '.'),
            'freight_cost' => number_format($product->freight_cost ?? 0, 2, ',', '.'),
            'tax_percentage' => number_format($product->tax_percentage ?? 0, 2, ',', '.'),
            'consumer_markup' => number_format($product->consumer_markup ?? 0, 2, ',', '.'),
            'distributor_markup' => number_format($product->distributor_markup ?? 0, 2, ',', '.'),
            'weight_kg' => number_format($product->weight_kg ?? 0, 3, ',', '.'),
            'unit_cost' => number_format($product->unit_cost ?? 0, 2, ',', '.'),
            'consumer_price' => number_format($product->consumer_price ?? 0, 2, ',', '.'),
            'distributor_price' => number_format($product->distributor_price ?? 0, 2, ',', '.')
        ];

        // Adiciona os valores formatados ao produto
        foreach ($formattedProduct as $key => $value) {
            $product->setAttribute($key . '_formatted', $value);
        }

        $categories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        $brands = Brand::where('status', true)
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::where('status', true)
            ->whereJsonContains('flag', 'fornecedor')
            ->orderBy('nome')
            ->get()
            ->map(function($supplier) {
                // Se for pessoa jurídica, usa razão social, senão usa nome
                $supplier->nome_display = $supplier->tipo_pessoa === 'J' 
                    ? $supplier->razao_social 
                    : $supplier->nome;
                return $supplier;
            });

        $distributorPriceLists = PriceList::where('type', 'distributor')->where('is_active', true)->get();
        $consumerPriceLists = PriceList::where('type', 'consumer')->where('is_active', true)->get();

        return view('products.edit', compact('product', 'categories', 'brands', 'suppliers', 'distributorPriceLists', 'consumerPriceLists'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            // Log dos dados recebidos
            \Log::info('Dados recebidos no update:', $request->all());

            // Remove formatação dos valores monetários e percentuais antes da validação
            $data = $request->all();
            $this->formatMonetaryAndPercentageFields($data);

            // Log dos dados após formatação
            \Log::info('Dados após formatação:', $data);

            // Cria uma nova request com os dados formatados
            $formattedRequest = new Request($data);

            // Valida os dados formatados
            $validatedData = $formattedRequest->validate(
                ProductValidation::rules($product),
                ProductValidation::messages()
            );

            // Log dos dados validados
            \Log::info('Dados validados:', $validatedData);

            DB::beginTransaction();

            // Trata a imagem se houver
            if ($request->hasFile('image')) {
                // Remove a imagem antiga se existir
                if ($product->image) {
                    $this->imageService->delete($product->image, 'produtos');
                }
                $validatedData['image'] = $this->imageService->store($request->file('image'), 'produtos');
            }

            // Prepara os dados para salvar
            $productData = array_merge($validatedData, [
                'status' => $request->boolean('status')
            ]);

            // Log dos dados finais
            \Log::info('Dados finais para update:', $productData);

            // Verifica se houve alteração nos preços
            $priceChanged = $product->unit_cost != $productData['unit_cost'] ||
                           $product->consumer_price != $productData['consumer_price'] ||
                           $product->distributor_price != $productData['distributor_price'];

            // Atualiza o produto
            $product->update($productData);

            // Registra o histórico de preços se houve alteração
            if ($priceChanged) {
                $this->updatePriceHistory($product, $productData, 'Atualização do produto');
            }

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'Produto atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao atualizar produto: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar produto: ' . $e->getMessage()]);
        }
    }

    public function updatePrices(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $product->cost_price = $request->cost_price;
            $product->price = $request->sale_price;
            $product->save();

            return redirect()
                ->back()
                ->with('success', 'Preços do produto atualizados com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao atualizar preços: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Exclui o histórico de preços
            PriceHistory::where('product_id', $product->id)->delete();

            // Remove a imagem se existir
            if ($product->image) {
                Storage::disk('public')->delete('produtos/' . $product->image);
            }

            // Força a exclusão permanente do produto
            $product->forceDelete();

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'Produto excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao excluir produto: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }

    public function priceHistory(Product $product)
    {
        $priceHistory = PriceHistory::with(['entry', 'user'])
            ->where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('products.price_history', compact('product', 'priceHistory'));
    }

    public function find(Request $request)
    {
        $query = $request->get('query');
        
        $products = Product::where('status', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->with(['category', 'brand'])
            ->limit(10)
            ->get();
            
        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * Remove formatação dos campos monetários e percentuais
     */
    private function formatMonetaryAndPercentageFields(&$data)
    {
        // Lista de campos monetários
        $moneyFields = [
            'last_purchase_price',
            'unit_cost',
            'consumer_price',
            'distributor_price'
        ];

        // Lista de campos percentuais
        $percentageFields = [
            'consumer_markup',
            'distributor_markup'
        ];

        // Lista de campos decimais
        $decimalFields = [
            'weight_kg'
        ];

        // Função auxiliar para limpar e converter valores
        $cleanValue = function($value) {
            // Se já for um número, retorna como float
            if (is_numeric($value)) {
                return (float) $value;
            }
            
            // Se for nulo, vazio ou não for string
            if (!is_string($value) || empty($value)) {
                return 0.0;
            }

            // Remove espaços e caracteres especiais exceto números, vírgula e ponto
            $value = trim(str_replace(['R$', '%', ' '], '', $value));
            
            // Se não houver números após a limpeza
            if ($value === '' || $value === ',' || $value === '.') {
                return 0.0;
            }

            // Substitui vírgula por ponto
            $value = str_replace(',', '.', $value);

            // Se houver mais de um ponto, mantém apenas o último como decimal
            if (substr_count($value, '.') > 1) {
                $parts = explode('.', $value);
                $decimal = array_pop($parts);
                $integer = implode('', $parts);
                $value = $integer . '.' . $decimal;
            }

            return (float) $value;
        };

        // Log antes da formatação
        \Log::info('Dados antes da formatação:', $data);

        // Processa campos monetários
        foreach ($moneyFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $cleanValue($data[$field]);
            }
        }

        // Processa campos percentuais
        foreach ($percentageFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $cleanValue($data[$field]);
            }
        }

        // Processa campos decimais
        foreach ($decimalFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = $cleanValue($data[$field]);
            }
        }

        // Log após a formatação
        \Log::info('Dados após a formatação:', $data);
    }

    /**
     * Registra o histórico de preços
     */
    private function updatePriceHistory(Product $product, $data, $reason = null, $entry_id = null)
    {
        try {
            // Log dos dados recebidos
            \Log::info('Dados recebidos para histórico:', $data);

            // Calcula os preços
            $lastPurchasePrice = $data['last_purchase_price'] ?? 0;
            $distributorMarkup = $data['distributor_markup'] ?? 0;
            $consumerMarkup = $data['consumer_markup'] ?? 0;

            $distributorPrice = $lastPurchasePrice * (1 + ($distributorMarkup / 100));
            $consumerPrice = $lastPurchasePrice * (1 + ($consumerMarkup / 100));

            // Cria o registro de histórico
            PriceHistory::create([
                'product_id' => $product->id,
                'purchase_price' => $lastPurchasePrice,
                'freight_cost' => $data['freight_cost'] ?? 0,
                'tax_percentage' => $data['tax_percentage'] ?? 0,
                'unit_cost' => $lastPurchasePrice, // Mesmo que purchase_price por enquanto
                'distributor_markup' => $distributorMarkup,
                'distributor_price' => $distributorPrice,
                'consumer_markup' => $consumerMarkup,
                'consumer_price' => $consumerPrice,
                'user_id' => auth()->id(),
                'reason' => $reason,
                'entry_id' => $entry_id
            ]);

            // Log do registro criado
            \Log::info('Histórico de preços criado com sucesso');

        } catch (\Exception $e) {
            \Log::error('Erro ao criar histórico de preços: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            throw $e;
        }
    }
}
