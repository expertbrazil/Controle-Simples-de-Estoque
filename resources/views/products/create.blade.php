@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Novo Produto
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nome do Produto <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
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
                                           value="{{ old('sku') }}" 
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
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
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
                                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                                           value="{{ old('last_purchase_price') }}" 
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
                                           value="{{ old('tax_percentage') }}" 
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
                                           value="{{ old('freight_cost') }}" 
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
                                           value="{{ old('weight_kg') }}" 
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
                                           value="{{ old('consumer_markup') }}" 
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
                                           value="{{ old('distributor_markup') }}" 
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
                                           readonly>
                                    <input type="hidden" 
                                           name="unit_cost" 
                                           value="{{ old('unit_cost', 0) }}">
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
                                           readonly>
                                    <input type="hidden" 
                                           name="consumer_price" 
                                           value="{{ old('consumer_price', 0) }}">
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
                                           readonly>
                                    <input type="hidden" 
                                           name="distributor_price" 
                                           value="{{ old('distributor_price', 0) }}">
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
                                       value="{{ old('min_stock') }}" 
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
                                       value="{{ old('max_stock') }}" 
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
                                       value="{{ old('stock_quantity', 0) }}" 
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
                                          rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="barcode" class="form-label">Código de Barras</label>
                                <input type="text" 
                                       class="form-control @error('barcode') is-invalid @enderror" 
                                       id="barcode" 
                                       name="barcode" 
                                       value="{{ old('barcode') }}">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Imagem do Produto</label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                               {{ old('status', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_yes">Ativo</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="status" 
                                               id="status_no" 
                                               value="0"
                                               {{ old('status', true) ? '' : 'checked' }}>
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

@push('scripts')
<script>
$(document).ready(function() {
    // Função para converter string formatada em número
    function parseFormattedNumber(value) {
        if (!value) return 0;
        return parseFloat(value.replace(/[^\d,.-]/g, '').replace(',', '.'));
    }

    // Função para formatar número como moeda
    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', { 
            minimumFractionDigits: 2, 
            maximumFractionDigits: 2 
        });
    }

    // Inicializa as máscaras
    $('.money').mask('#.##0,00', { 
        reverse: true,
        onChange: function(value, e) {
            calculatePrices();
        }
    });
    
    $('.percentage').mask('##0,00', { 
        reverse: true,
        onChange: function(value, e) {
            calculatePrices();
        }
    });
    
    $('.decimal').mask('##0,000', { 
        reverse: true,
        onChange: function(value, e) {
            calculatePrices();
        }
    });

    // Função para calcular os preços
    function calculatePrices() {
        // Obtém os valores dos campos
        var lastPurchasePrice = parseFormattedNumber($('#last_purchase_price').val());
        var taxPercentage = parseFormattedNumber($('#tax_percentage').val());
        var freightCost = parseFormattedNumber($('#freight_cost').val());
        var consumerMarkup = parseFormattedNumber($('#consumer_markup').val());
        var distributorMarkup = parseFormattedNumber($('#distributor_markup').val());
        var weightKg = parseFormattedNumber($('#weight_kg').val());

        console.log('Valores para cálculo:', {
            lastPurchasePrice,
            taxPercentage,
            freightCost,
            consumerMarkup,
            distributorMarkup,
            weightKg
        });

        // Calcula o custo unitário considerando o peso
        var freightPerUnit = weightKg > 0 ? freightCost * weightKg : freightCost;
        var unitCost = lastPurchasePrice * (1 + (taxPercentage/100)) + freightPerUnit;
        
        // Calcula o preço consumidor
        var consumerPrice = unitCost * (1 + (consumerMarkup/100));
        
        // Calcula o preço distribuidor
        var distributorPrice = unitCost * (1 + (distributorMarkup/100));

        console.log('Resultados calculados:', {
            freightPerUnit,
            unitCost,
            consumerPrice,
            distributorPrice
        });

        // Atualiza os campos de exibição
        $('#unit_cost').val(formatCurrency(unitCost));
        $('#consumer_price').val(formatCurrency(consumerPrice));
        $('#distributor_price').val(formatCurrency(distributorPrice));

        // Atualiza os campos hidden
        $('input[name="unit_cost"]').val(unitCost.toFixed(2));
        $('input[name="consumer_price"]').val(consumerPrice.toFixed(2));
        $('input[name="distributor_price"]').val(distributorPrice.toFixed(2));
    }

    // Eventos para recalcular os preços
    $('#last_purchase_price, #tax_percentage, #freight_cost, #consumer_markup, #distributor_markup, #weight_kg')
        .on('input', calculatePrices)
        .on('change', calculatePrices);

    // Calcula os preços iniciais
    calculatePrices();
});
</script>
@endpush

@endsection
