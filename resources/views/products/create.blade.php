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
                            <div class="col-md-4 mb-3">
                                <label for="last_purchase_price" class="form-label">Último Preço de Compra <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('last_purchase_price') is-invalid @enderror" 
                                       id="last_purchase_price" 
                                       name="last_purchase_price" 
                                       value="{{ old('last_purchase_price') }}" 
                                       required>
                                @error('last_purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tax_percentage" class="form-label">Percentual de Imposto <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('tax_percentage') is-invalid @enderror" 
                                       id="tax_percentage" 
                                       name="tax_percentage" 
                                       value="{{ old('tax_percentage') }}" 
                                       required>
                                @error('tax_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="freight_cost" class="form-label">Custo de Frete <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('freight_cost') is-invalid @enderror" 
                                       id="freight_cost" 
                                       name="freight_cost" 
                                       value="{{ old('freight_cost') }}" 
                                       required>
                                @error('freight_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="weight_kg" class="form-label">Peso (kg) <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('weight_kg') is-invalid @enderror" 
                                       id="weight_kg" 
                                       name="weight_kg" 
                                       value="{{ old('weight_kg') }}" 
                                       required>
                                @error('weight_kg')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="consumer_markup" class="form-label">Markup Consumidor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('consumer_markup') is-invalid @enderror" 
                                       id="consumer_markup" 
                                       name="consumer_markup" 
                                       value="{{ old('consumer_markup') }}" 
                                       required>
                                @error('consumer_markup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="distributor_markup" class="form-label">Markup Distribuidor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('distributor_markup') is-invalid @enderror" 
                                       id="distributor_markup" 
                                       name="distributor_markup" 
                                       value="{{ old('distributor_markup') }}" 
                                       required>
                                @error('distributor_markup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="consumer_price" class="form-label">Preço Consumidor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('consumer_price') is-invalid @enderror" 
                                       id="consumer_price" 
                                       name="consumer_price" 
                                       value="{{ old('consumer_price') }}" 
                                       required>
                                @error('consumer_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="distributor_price" class="form-label">Preço Distribuidor <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('distributor_price') is-invalid @enderror" 
                                       id="distributor_price" 
                                       name="distributor_price" 
                                       value="{{ old('distributor_price') }}" 
                                       required>
                                @error('distributor_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="image" class="form-label">Imagem do Produto</label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
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
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
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
        // Máscaras para campos monetários
        $('#last_purchase_price, #freight_cost, #consumer_price, #distributor_price').mask('000.000.000.000.000,00', {reverse: true});
        $('#tax_percentage, #consumer_markup, #distributor_markup').mask('000,00%', {reverse: true});
        $('#weight_kg').mask('000,000 kg', {reverse: true});
    });
</script>
@endpush
@endsection
