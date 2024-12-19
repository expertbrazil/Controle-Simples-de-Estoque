@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Editar Produto
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $product->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('sku') is-invalid @enderror" 
                                           id="sku" 
                                           name="sku" 
                                           value="{{ old('sku', $product->sku) }}" 
                                           required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="category_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">Selecione uma categoria</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="brand_id" class="form-label">Marca <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                                        <option value="">Selecione uma marca</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#brandModal">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="supplier_id" class="form-label">Fornecedor <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                        <option value="">Selecione um fornecedor</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->nome_display }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalFornecedor">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="last_purchase_price" class="form-label">Último Preço de Compra <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" 
                                           class="form-control money @error('last_purchase_price') is-invalid @enderror" 
                                           id="last_purchase_price" 
                                           name="last_purchase_price" 
                                           value="{{ old('last_purchase_price', number_format($product->last_purchase_price, 2, ',', '.')) }}" 
                                           required>
                                </div>
                                @error('last_purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tax_percentage" class="form-label">Percentual de Imposto <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control percentage @error('tax_percentage') is-invalid @enderror" 
                                           id="tax_percentage" 
                                           name="tax_percentage" 
                                           value="{{ old('tax_percentage', number_format($product->tax_percentage, 2, ',', '.')) }}" 
                                           required>
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('tax_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="freight_cost" class="form-label">Custo de Frete <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" 
                                           class="form-control money @error('freight_cost') is-invalid @enderror" 
                                           id="freight_cost" 
                                           name="freight_cost" 
                                           value="{{ old('freight_cost', number_format($product->freight_cost, 2, ',', '.')) }}" 
                                           required>
                                </div>
                                @error('freight_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="weight_kg" class="form-label">Peso (kg) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control decimal @error('weight_kg') is-invalid @enderror" 
                                           id="weight_kg" 
                                           name="weight_kg" 
                                           value="{{ old('weight_kg', number_format($product->weight_kg, 3, ',', '.')) }}" 
                                           required>
                                    <span class="input-group-text">kg</span>
                                </div>
                                @error('weight_kg')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="consumer_markup" class="form-label">Margem Consumidor <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control percentage @error('consumer_markup') is-invalid @enderror" 
                                           id="consumer_markup" 
                                           name="consumer_markup" 
                                           value="{{ old('consumer_markup', number_format($product->consumer_markup, 2, ',', '.')) }}" 
                                           required>
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('consumer_markup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="distributor_markup" class="form-label">Margem Distribuidor <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control percentage @error('distributor_markup') is-invalid @enderror" 
                                           id="distributor_markup" 
                                           name="distributor_markup" 
                                           value="{{ old('distributor_markup', number_format($product->distributor_markup, 2, ',', '.')) }}" 
                                           required>
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('distributor_markup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="unit_cost" class="form-label">Custo Unitário</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="unit_cost" 
                                           value="{{ number_format($product->unit_cost, 2, ',', '.') }}"
                                           readonly>
                                    <input type="hidden" 
                                           name="unit_cost" 
                                           value="{{ old('unit_cost', $product->unit_cost) }}">
                                </div>
                                <small class="text-muted">Calculado automaticamente</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="consumer_price" class="form-label">Preço Consumidor</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="consumer_price" 
                                           value="{{ number_format($product->consumer_price, 2, ',', '.') }}"
                                           readonly>
                                    <input type="hidden" 
                                           name="consumer_price" 
                                           value="{{ old('consumer_price', $product->consumer_price) }}">
                                </div>
                                <small class="text-muted">Calculado automaticamente</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="distributor_price" class="form-label">Preço Distribuidor</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="distributor_price" 
                                           value="{{ number_format($product->distributor_price, 2, ',', '.') }}"
                                           readonly>
                                    <input type="hidden" 
                                           name="distributor_price" 
                                           value="{{ old('distributor_price', $product->distributor_price) }}">
                                </div>
                                <small class="text-muted">Calculado automaticamente</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="min_stock" class="form-label">Estoque Mínimo <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('min_stock') is-invalid @enderror" 
                                       id="min_stock" 
                                       name="min_stock" 
                                       value="{{ old('min_stock', $product->min_stock) }}" 
                                       required>
                                @error('min_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="max_stock" class="form-label">Estoque Máximo <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('max_stock') is-invalid @enderror" 
                                       id="max_stock" 
                                       name="max_stock" 
                                       value="{{ old('max_stock', $product->max_stock) }}" 
                                       required>
                                @error('max_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="stock_quantity" class="form-label">Quantidade em Estoque <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('stock_quantity') is-invalid @enderror" 
                                       id="stock_quantity" 
                                       name="stock_quantity" 
                                       value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                       required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Descrição</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Imagem do Produto</label>
                                @if($product->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('images/produtos/' . $product->image) }}" 
                                             alt="Imagem atual" 
                                             class="img-thumbnail"
                                             style="max-height: 200px;">
                                    </div>
                                @endif
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image"
                                       accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Deixe em branco para manter a imagem atual</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Status do Produto</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="status" 
                                               id="status_yes" 
                                               value="1"
                                               {{ old('status', $product->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_yes">Ativo</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="status" 
                                               id="status_no" 
                                               value="0"
                                               {{ old('status', $product->status) ? '' : 'checked' }}>
                                        <label class="form-check-label" for="status_no">Inativo</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Salvar
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}
.invalid-feedback {
    font-size: 80%;
}
.image-upload-container {
    position: relative;
}
.progress {
    height: 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Função para formatar número como moeda
    function formatMoney(value) {
        return value.toLocaleString('pt-BR', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        });
    }

    // Função para converter string formatada em número
    function parseFormattedNumber(value) {
        if (!value) return 0;
        return parseFloat(value.replace(/\./g, '').replace(',', '.'));
    }

    // Função para calcular todos os valores
    function calculatePrices() {
        // Valores de entrada
        const purchasePrice = parseFormattedNumber($('#last_purchase_price').val());
        const taxPercentage = parseFormattedNumber($('#tax_percentage').val());
        const freightCost = parseFormattedNumber($('#freight_cost').val());
        const weightKg = parseFormattedNumber($('#weight_kg').val());
        const consumerMarkup = parseFormattedNumber($('#consumer_markup').val());
        const distributorMarkup = parseFormattedNumber($('#distributor_markup').val());

        // Cálculo do imposto
        const taxValue = purchasePrice * (taxPercentage / 100);

        // Cálculo do frete por peso
        const freightTotal = freightCost * weightKg;

        // Cálculo do custo unitário
        const unitCost = purchasePrice + taxValue + freightTotal;

        // Cálculo dos preços finais
        const consumerPrice = unitCost * (1 + (consumerMarkup / 100));
        const distributorPrice = unitCost * (1 + (distributorMarkup / 100));

        // Atualiza os campos de exibição
        $('#unit_cost').val(formatMoney(unitCost));
        $('#consumer_price').val(formatMoney(consumerPrice));
        $('#distributor_price').val(formatMoney(distributorPrice));

        // Atualiza os campos hidden com valores não formatados
        $('input[name="unit_cost"]').val(unitCost.toFixed(2));
        $('input[name="consumer_price"]').val(consumerPrice.toFixed(2));
        $('input[name="distributor_price"]').val(distributorPrice.toFixed(2));

        // Log para debug
        console.log('Valores calculados:', {
            'Valor da compra': purchasePrice,
            'Imposto (%)': taxPercentage,
            'Valor do imposto': taxValue,
            'Frete': freightCost,
            'Peso (kg)': weightKg,
            'Frete total': freightTotal,
            'Custo unitário': unitCost,
            'Markup consumidor (%)': consumerMarkup,
            'Preço consumidor': consumerPrice,
            'Markup distribuidor (%)': distributorMarkup,
            'Preço distribuidor': distributorPrice
        });
    }

    // Máscara para os campos
    $('.money').mask('#.##0,00', { 
        reverse: true,
        onChange: calculatePrices
    });
    
    $('.percentage').mask('##0,00', { 
        reverse: true,
        onChange: calculatePrices
    });
    
    $('.weight').mask('##0,000', { 
        reverse: true,
        onChange: calculatePrices
    });

    // Calcula os preços quando qualquer campo é alterado
    $('#last_purchase_price, #tax_percentage, #freight_cost, #weight_kg, #consumer_markup, #distributor_markup')
        .on('input', calculatePrices)
        .on('change', calculatePrices);

    // Calcula os preços iniciais
    calculatePrices();
});
</script>
@endpush

@endsection
