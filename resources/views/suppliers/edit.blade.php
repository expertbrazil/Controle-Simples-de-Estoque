@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h2 class="mt-4">Editar Fornecedor</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Fornecedores</a></li>
            <li class="breadcrumb-item active">Editar Fornecedor</li>
        </ol>
    </div>

    @include('layouts.messages')

    <div class="card">
        <div class="card-header">
            Edição de Fornecedor
        </div>
        <div class="card-body">
            <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $supplier->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="cnpj" class="form-label">CNPJ</label>
                        <input type="text" class="form-control @error('cnpj') is-invalid @enderror" id="cnpj" name="cnpj" value="{{ old('cnpj', $supplier->cnpj) }}">
                        @error('cnpj')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $supplier->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="zip_code" class="form-label">CEP</label>
                        <input type="text" class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" name="zip_code" value="{{ old('zip_code', $supplier->zip_code) }}">
                        @error('zip_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8 mb-3">
                        <label for="address" class="form-label">Endereço</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $supplier->address) }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="neighborhood" class="form-label">Bairro</label>
                        <input type="text" class="form-control @error('neighborhood') is-invalid @enderror" id="neighborhood" name="neighborhood" value="{{ old('neighborhood', $supplier->neighborhood) }}">
                        @error('neighborhood')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="city" class="form-label">Cidade</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $supplier->city) }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="state" class="form-label">Estado</label>
                        <select class="form-select @error('state') is-invalid @enderror" id="state" name="state">
                            <option value="">Selecione o estado</option>
                            <option value="AC" {{ old('state', $supplier->state) == 'AC' ? 'selected' : '' }}>Acre</option>
                            <option value="AL" {{ old('state', $supplier->state) == 'AL' ? 'selected' : '' }}>Alagoas</option>
                            <option value="AP" {{ old('state', $supplier->state) == 'AP' ? 'selected' : '' }}>Amapá</option>
                            <option value="AM" {{ old('state', $supplier->state) == 'AM' ? 'selected' : '' }}>Amazonas</option>
                            <option value="BA" {{ old('state', $supplier->state) == 'BA' ? 'selected' : '' }}>Bahia</option>
                            <option value="CE" {{ old('state', $supplier->state) == 'CE' ? 'selected' : '' }}>Ceará</option>
                            <option value="DF" {{ old('state', $supplier->state) == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                            <option value="ES" {{ old('state', $supplier->state) == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                            <option value="GO" {{ old('state', $supplier->state) == 'GO' ? 'selected' : '' }}>Goiás</option>
                            <option value="MA" {{ old('state', $supplier->state) == 'MA' ? 'selected' : '' }}>Maranhão</option>
                            <option value="MT" {{ old('state', $supplier->state) == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                            <option value="MS" {{ old('state', $supplier->state) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                            <option value="MG" {{ old('state', $supplier->state) == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                            <option value="PA" {{ old('state', $supplier->state) == 'PA' ? 'selected' : '' }}>Pará</option>
                            <option value="PB" {{ old('state', $supplier->state) == 'PB' ? 'selected' : '' }}>Paraíba</option>
                            <option value="PR" {{ old('state', $supplier->state) == 'PR' ? 'selected' : '' }}>Paraná</option>
                            <option value="PE" {{ old('state', $supplier->state) == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                            <option value="PI" {{ old('state', $supplier->state) == 'PI' ? 'selected' : '' }}>Piauí</option>
                            <option value="RJ" {{ old('state', $supplier->state) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                            <option value="RN" {{ old('state', $supplier->state) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                            <option value="RS" {{ old('state', $supplier->state) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                            <option value="RO" {{ old('state', $supplier->state) == 'RO' ? 'selected' : '' }}>Rondônia</option>
                            <option value="RR" {{ old('state', $supplier->state) == 'RR' ? 'selected' : '' }}>Roraima</option>
                            <option value="SC" {{ old('state', $supplier->state) == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                            <option value="SP" {{ old('state', $supplier->state) == 'SP' ? 'selected' : '' }}>São Paulo</option>
                            <option value="SE" {{ old('state', $supplier->state) == 'SE' ? 'selected' : '' }}>Sergipe</option>
                            <option value="TO" {{ old('state', $supplier->state) == 'TO' ? 'selected' : '' }}>Tocantins</option>
                        </select>
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="contact_name" class="form-label">Nome do Contato</label>
                    <input type="text" class="form-control @error('contact_name') is-invalid @enderror" id="contact_name" name="contact_name" value="{{ old('contact_name', $supplier->contact_name) }}">
                    @error('contact_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active', $supplier->active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Ativo</label>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Máscaras
        $('#cnpj').mask('00.000.000/0000-00');
        $('#phone').mask('(00) 00000-0000');
        $('#zip_code').mask('00000-000');

        // Busca de CEP
        $('#zip_code').on('blur', function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep.length === 8) {
                $.get(`https://viacep.com.br/ws/${cep}/json/`)
                    .done(function(data) {
                        if (!data.erro) {
                            $('#address').val(data.logradouro);
                            $('#neighborhood').val(data.bairro);
                            $('#city').val(data.localidade);
                            $('#state').val(data.uf);
                        }
                    });
            }
        });
    });
</script>
@endpush
@endsection
