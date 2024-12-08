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

                        <div class="row g-3">
                            <div class="col-md-8">
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

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" 
                                           class="form-control @error('sku') is-invalid @enderror" 
                                           id="sku" 
                                           name="sku" 
                                           value="{{ old('sku') }}">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                    <label for="category_id" class="form-label">Categoria</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" 
                                            name="category_id">
                                        <option value="">Selecione uma categoria</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price" class="form-label">Preço de Venda <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               id="price" 
                                               name="price" 
                                               value="{{ old('price') }}" 
                                               step="0.01" 
                                               required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cost_price" class="form-label">Preço de Custo</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" 
                                               class="form-control @error('cost_price') is-invalid @enderror" 
                                               id="cost_price" 
                                               name="cost_price" 
                                               value="{{ old('cost_price') }}" 
                                               step="0.01">
                                        @error('cost_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
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
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                imagePreview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);

            // Simulate upload progress
            progressBar.classList.remove('d-none');
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                progressBarInner.style.width = progress + '%';
                progressBarInner.setAttribute('aria-valuenow', progress);
                
                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        progressBar.classList.add('d-none');
                        progressBarInner.style.width = '0%';
                    }, 500);
                }
            }, 100);
        }
    });
});
</script>
@endpush
@endsection
