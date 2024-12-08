@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-person-gear"></i> Editar Cliente
        </h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nome do Cliente</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $customer->name) }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="phone" class="form-label">Telefone</label>
                    <input type="text" class="form-control phone @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input @error('active') is-invalid @enderror" 
                               id="active" name="active" value="1" 
                               {{ old('active', $customer->active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Cliente Ativo</label>
                        @error('active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('customers.index') }}" class="btn btn-light me-md-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Atualizar Cliente</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.phone').mask('(00) 00000-0000');
    });
</script>
@endsection
