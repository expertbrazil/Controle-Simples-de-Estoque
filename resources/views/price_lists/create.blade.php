@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Nova Lista de Preços</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('price-lists.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nome da Lista <span class="text-danger">*</span></label>
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

                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Tipo <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="">Selecione...</option>
                                    <option value="distributor" {{ old('type') === 'distributor' ? 'selected' : '' }}>Distribuidor</option>
                                    <option value="consumer" {{ old('type') === 'consumer' ? 'selected' : '' }}>Consumidor</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="markup_percentage" class="form-label">Markup (%) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control percentage @error('markup_percentage') is-invalid @enderror" 
                                           id="markup_percentage" 
                                           name="markup_percentage" 
                                           value="{{ old('markup_percentage') }}" 
                                           required>
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('markup_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" 
                                           class="form-check-input" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', '1') === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Ativo</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar
                                </button>
                                <a href="{{ route('price-lists.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
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

@push('scripts')
<script>
$(document).ready(function() {
    $('.percentage').mask('##0,00', {reverse: true});
});
</script>
@endpush
