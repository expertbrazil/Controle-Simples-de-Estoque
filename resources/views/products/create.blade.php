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
                            <div class="col-md-6">
                                <div class="form-group mb-3">
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
                            <div class="col-md-6">
                                <div class="form-group mb-3">
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
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
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

                            <div class="col-md-4 mb-3">
                                <label for="brand_id" class="form-label">Marca <span class="text-danger">*</span></label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
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

                            <div class="col-md-4 mb-3">
                                <label for="supplier_id" class="form-label">Fornecedor <span class="text-danger">*</span></label>
                                <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
                                    <option value="">Selecione um fornecedor</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="last_purchase_price" class="form-label">Preço de Compra (R$)</label>
                                    <input type="text" 
                                           class="form-control money @error('last_purchase_price') is-invalid @enderror" 
                                           id="last_purchase_price" 
                                           name="last_purchase_price" 
                                           value="{{ old('last_purchase_price') }}">
                                    @error('last_purchase_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="tax_percentage" class="form-label">Impostos (%)</label>
                                    <input type="number" 
                                           step="0.01" 
                                           class="form-control @error('tax_percentage') is-invalid @enderror" 
                                           id="tax_percentage" 
                                           name="tax_percentage" 
                                           value="{{ old('tax_percentage', 0) }}">
                                    @error('tax_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="freight_cost" class="form-label">Custo do Frete (R$)</label>
                                    <input type="text" 
                                           class="form-control money @error('freight_cost') is-invalid @enderror" 
                                           id="freight_cost" 
                                           name="freight_cost" 
                                           value="{{ old('freight_cost') }}">
                                    @error('freight_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="weight_kg" class="form-label">Peso (kg)</label>
                                    <input type="number" 
                                           step="0.001" 
                                           class="form-control @error('weight_kg') is-invalid @enderror" 
                                           id="weight_kg" 
                                           name="weight_kg" 
                                           value="{{ old('weight_kg', 0) }}">
                                    @error('weight_kg')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="unit_cost" class="form-label">Custo por Unidade (R$)</label>
                                    <input type="text" 
                                           class="form-control money" 
                                           id="unit_cost" 
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-4">Preços e Markups</h5>
                        <div class="row">
                            <!-- Consumidor Final -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Preço Consumidor Final</h6>
                                        <div class="form-group mb-3">
                                            <label for="consumer_markup" class="form-label">Markup Consumidor (%)</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   class="form-control @error('consumer_markup') is-invalid @enderror" 
                                                   id="consumer_markup" 
                                                   name="consumer_markup" 
                                                   value="{{ old('consumer_markup', 0) }}">
                                            @error('consumer_markup')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="consumer_price" class="form-label">Preço Consumidor (R$)</label>
                                            <input type="text" 
                                                   class="form-control money @error('consumer_price') is-invalid @enderror" 
                                                   id="consumer_price" 
                                                   name="consumer_price" 
                                                   value="{{ old('consumer_price') }}">
                                            @error('consumer_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Distribuidora -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Preço Distribuidora</h6>
                                        <div class="form-group mb-3">
                                            <label for="distributor_markup" class="form-label">Markup Distribuidor (%)</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   class="form-control @error('distributor_markup') is-invalid @enderror" 
                                                   id="distributor_markup" 
                                                   name="distributor_markup" 
                                                   value="{{ old('distributor_markup', 0) }}">
                                            @error('distributor_markup')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="distributor_price" class="form-label">Preço Distribuidor (R$)</label>
                                            <input type="text" 
                                                   class="form-control money @error('distributor_price') is-invalid @enderror" 
                                                   id="distributor_price" 
                                                   name="distributor_price" 
                                                   value="{{ old('distributor_price') }}">
                                            @error('distributor_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
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

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stock_quantity" class="form-label">Quantidade em Estoque <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('stock_quantity') is-invalid @enderror" 
                                       id="stock_quantity" 
                                       name="stock_quantity" 
                                       value="{{ old('stock_quantity', 0) }}" 
                                       min="0" 
                                       required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="min_stock" class="form-label">Estoque Mínimo</label>
                                <input type="number" 
                                       class="form-control @error('min_stock') is-invalid @enderror" 
                                       id="min_stock" 
                                       name="min_stock" 
                                       value="{{ old('min_stock', 0) }}" 
                                       min="0">
                                @error('min_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="active" 
                                           id="active_yes" 
                                           value="1" 
                                           {{ old('active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active_yes">Ativo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="active" 
                                           id="active_no" 
                                           value="0" 
                                           {{ old('active') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active_no">Inativo</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="image" class="form-label">Imagem do Produto</label>
                                <div class="image-upload-container">
                                    <input type="file" 
                                           class="form-control @error('image') is-invalid @enderror" 
                                           id="image" 
                                           name="image" 
                                           accept="image/*">
                                    <input type="hidden" name="stored_image" id="stored_image">
                                    <div id="image-preview" class="mt-2 d-none">
                                        <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                    <div class="progress mt-2 d-none">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar Produto
                            </button>
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
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const previewImage = imagePreview.querySelector('img');
    const progressBar = document.querySelector('.progress');
    const progressBarInner = progressBar.querySelector('.progress-bar');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Mostrar preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                imagePreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);

            // Upload image
            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            progressBar.classList.remove('d-none');
            progressBarInner.style.width = '0%';
            progressBarInner.setAttribute('aria-valuenow', 0);

            fetch('{{ route('products.upload-image') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('stored_image').value = data.fileName;
                    progressBarInner.style.width = '100%';
                    progressBarInner.setAttribute('aria-valuenow', 100);
                    setTimeout(() => {
                        progressBar.classList.add('d-none');
                    }, 500);
                } else {
                    throw new Error(data.message || 'Erro ao fazer upload da imagem');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Erro ao fazer upload da imagem');
                imagePreview.classList.add('d-none');
                progressBar.classList.add('d-none');
            });
        }
    });
});

$(document).ready(function() {
    // Função para calcular o custo unitário
    function calculateUnitCost() {
        const purchasePrice = parseFloat($('#last_purchase_price').val().replace('.', '').replace(',', '.')) || 0;
        const taxPercentage = parseFloat($('#tax_percentage').val()) || 0;
        const freightCost = parseFloat($('#freight_cost').val().replace('.', '').replace(',', '.')) || 0;
        const weightKg = parseFloat($('#weight_kg').val()) || 0;

        // Calcula o valor dos impostos
        const taxAmount = purchasePrice * (taxPercentage / 100);
        
        // Calcula o custo do frete por unidade
        const freightPerUnit = weightKg > 0 ? (freightCost / weightKg) : 0;
        
        // Calcula o custo total por unidade
        const unitCost = purchasePrice + taxAmount + freightPerUnit;
        
        // Atualiza o campo de custo unitário
        $('#unit_cost').val(unitCost.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        
        return unitCost;
    }

    // Função para calcular preços baseados no markup
    function calculatePrices() {
        const unitCost = calculateUnitCost();

        // Calcula preço consumidor
        const consumerMarkup = parseFloat($('#consumer_markup').val()) || 0;
        if (unitCost > 0 && consumerMarkup > 0) {
            const consumerPrice = unitCost * (1 + (consumerMarkup / 100));
            $('#consumer_price').val(consumerPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        }

        // Calcula preço distribuidor
        const distributorMarkup = parseFloat($('#distributor_markup').val()) || 0;
        if (unitCost > 0 && distributorMarkup > 0) {
            const distributorPrice = unitCost * (1 + (distributorMarkup / 100));
            $('#distributor_price').val(distributorPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
        }
    }

    // Eventos para recalcular os valores
    $('#last_purchase_price, #tax_percentage, #freight_cost, #weight_kg').on('input', function() {
        calculatePrices();
    });

    $('#consumer_markup, #distributor_markup').on('input', function() {
        calculatePrices();
    });

    // Inicializa os cálculos
    calculatePrices();
});
</script>
@endpush
@endsection
