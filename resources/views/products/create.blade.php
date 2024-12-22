@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Novo Produto</h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <!-- Nome e SKU -->
                            <div class="col-md-6">
                                <label class="form-label">Nome do Produto *</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Código de Barras</label>
                                <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Categoria, Marca e Fornecedor -->
                            <div class="col-md-4">
                                <label class="form-label">Categoria *</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Marca *</label>
                                <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                    <option value="">Selecione uma marca</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fornecedor Principal</label>
                                <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                                    <option value="">Selecione um fornecedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Descrição -->
                            <div class="col-12">
                                <label class="form-label">Descrição</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preços e Custos -->
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
                                    <label for="weight_kg" class="form-label">Peso (kg) <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control decimal @error('weight_kg') is-invalid @enderror" 
                                           id="weight_kg" 
                                           name="weight_kg" 
                                           value="{{ old('weight_kg', '0,00') }}"
                                           required>
                                    @error('weight_kg')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="cost_price" class="form-label">Preço de Custo</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" 
                                               class="form-control money" 
                                               id="cost_price" 
                                               readonly>
                                    </div>
                                    <small class="text-muted">Calculado automaticamente</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="distributor_price_list" class="form-label">Lista de Preços Distribuidor *</label>
                                    <select class="form-select @error('distributor_price_list') is-invalid @enderror" 
                                            id="distributor_price_list" 
                                            name="distributor_price_list"
                                            required>
                                        <option value="">Selecione uma lista de preços</option>
                                        @foreach($distributorPriceLists as $list)
                                            <option value="{{ $list->id }}" 
                                                    data-markup="{{ $list->markup_percentage }}"
                                                    {{ old('distributor_price_list') == $list->id ? 'selected' : '' }}>
                                                {{ $list->name }} ({{ $list->markup_percentage }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('distributor_price_list')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="distributor_price" class="form-label">Preço Distribuidor</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" 
                                               class="form-control money" 
                                               id="distributor_price" 
                                               readonly>
                                    </div>
                                    <small class="text-muted">Calculado automaticamente</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="consumer_price_list" class="form-label">Lista de Preços Consumidor *</label>
                                    <select class="form-select @error('consumer_price_list') is-invalid @enderror" 
                                            id="consumer_price_list" 
                                            name="consumer_price_list"
                                            required>
                                        <option value="">Selecione uma lista de preços</option>
                                        @foreach($consumerPriceLists as $list)
                                            <option value="{{ $list->id }}" 
                                                    data-markup="{{ $list->markup_percentage }}"
                                                    {{ old('consumer_price_list') == $list->id ? 'selected' : '' }}>
                                                {{ $list->name }} ({{ $list->markup_percentage }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('consumer_price_list')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="consumer_price" class="form-label">Preço Consumidor</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="text" 
                                               class="form-control money" 
                                               id="consumer_price" 
                                               readonly>
                                    </div>
                                    <small class="text-muted">Calculado automaticamente</small>
                                </div>
                            </div>

                            <!-- Campos ocultos para os markups e preços -->
                            <input type="hidden" name="distributor_markup" id="distributor_markup" value="{{ old('distributor_markup', 0) }}">
                            <input type="hidden" name="consumer_markup" id="consumer_markup" value="{{ old('consumer_markup', 0) }}">
                            <input type="hidden" name="unit_cost" id="unit_cost" value="{{ old('unit_cost', 0) }}">
                            <input type="hidden" name="distributor_price" value="{{ old('distributor_price', 0) }}">
                            <input type="hidden" name="consumer_price" value="{{ old('consumer_price', 0) }}">

                            <!-- Estoque -->
                            <div class="col-md-3">
                                <label class="form-label">Estoque Atual</label>
                                <input type="number" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', 0) }}">
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Estoque Mínimo</label>
                                <input type="number" name="min_stock" class="form-control @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', 0) }}">
                                @error('min_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Estoque Máximo</label>
                                <input type="number" name="max_stock" class="form-control @error('max_stock') is-invalid @enderror" value="{{ old('max_stock', 0) }}">
                                @error('max_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Imagem -->
                            <div class="col-12">
                                <label class="form-label">Imagem do Produto</label>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="status" class="form-check-input" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                    <label class="form-check-label">Produto Ativo</label>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="col-12">
                                <hr>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Salvar Produto
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.form-select').select2({
        theme: 'bootstrap-5'
    });

    // Máscaras para campos monetários e percentuais
    $('.money').mask('#.##0,00', {reverse: true});
    $('.decimal').mask('#.##0,00', {reverse: true});

    // Função para converter string em número
    function parseDecimal(value) {
        if (!value) return 0;
        return parseFloat(value.replace(/\./g, '').replace(',', '.'));
    }

    // Função para formatar número como moeda
    function formatMoney(value) {
        return value.toFixed(2).replace('.', ',');
    }

    // Função para calcular custo unitário
    function calculateUnitCost() {
        let purchasePrice = parseDecimal($('input[name="last_purchase_price"]').val());
        return purchasePrice;
    }

    // Função para calcular preço com markup
    function calculatePriceWithMarkup(unitCost, markup) {
        return unitCost * (1 + (markup / 100));
    }

    // Função para atualizar todos os preços
    function updateAllPrices() {
        // Calcula o custo unitário
        let unitCost = calculateUnitCost();
        
        // Obtém os markups das listas selecionadas
        let distributorMarkup = parseFloat($('#distributor_price_list option:selected').data('markup')) || 0;
        let consumerMarkup = parseFloat($('#consumer_price_list option:selected').data('markup')) || 0;

        // Calcula os preços
        let distributorPrice = calculatePriceWithMarkup(unitCost, distributorMarkup);
        let consumerPrice = calculatePriceWithMarkup(unitCost, consumerMarkup);

        // Atualiza os campos de exibição
        $('#cost_price').val(formatMoney(unitCost));
        $('#distributor_price').val(formatMoney(distributorPrice));
        $('#consumer_price').val(formatMoney(consumerPrice));

        // Atualiza os campos ocultos
        $('input[name="unit_cost"]').val(unitCost.toFixed(2));
        $('input[name="distributor_markup"]').val(distributorMarkup.toFixed(2));
        $('input[name="consumer_markup"]').val(consumerMarkup.toFixed(2));
        $('input[name="distributor_price"]').val(distributorPrice.toFixed(2));
        $('input[name="consumer_price"]').val(consumerPrice.toFixed(2));
    }

    // Eventos para recalcular preços
    $('input[name="last_purchase_price"]').on('change keyup', updateAllPrices);
    $('#distributor_price_list, #consumer_price_list').on('change', function() {
        updateAllPrices();
    });

    // Calcular preços iniciais
    updateAllPrices();
});
</script>
@endpush
