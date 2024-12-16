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
                        <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome', $supplier->nome) }}" required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="tipo_pessoa" class="form-label">Tipo Pessoa <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo_pessoa') is-invalid @enderror" id="tipo_pessoa" name="tipo_pessoa" required>
                            <option value="J" {{ old('tipo_pessoa', $supplier->tipo_pessoa) == 'J' ? 'selected' : '' }}>Jurídica</option>
                            <option value="F" {{ old('tipo_pessoa', $supplier->tipo_pessoa) == 'F' ? 'selected' : '' }}>Física</option>
                        </select>
                        @error('tipo_pessoa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="documento" class="form-label">CPF/CNPJ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('documento') is-invalid @enderror" id="documento" name="documento" value="{{ old('documento', $supplier->documento) }}" required>
                        @error('documento')
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
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" value="{{ old('cep', $supplier->cep) }}">
                        @error('cep')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="rua" class="form-label">Rua</label>
                        <input type="text" class="form-control @error('rua') is-invalid @enderror" id="rua" name="rua" value="{{ old('rua', $supplier->rua) }}">
                        @error('rua')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero', $supplier->numero) }}">
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control @error('complemento') is-invalid @enderror" id="complemento" name="complemento" value="{{ old('complemento', $supplier->complemento) }}">
                        @error('complemento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ old('bairro', $supplier->bairro) }}">
                        @error('bairro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ old('cidade', $supplier->cidade) }}">
                        @error('cidade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="uf" class="form-label">Estado</label>
                        <select class="form-select @error('uf') is-invalid @enderror" id="uf" name="uf">
                            <option value="">Selecione o estado</option>
                            <option value="AC" {{ old('uf', $supplier->uf) == 'AC' ? 'selected' : '' }}>Acre</option>
                            <option value="AL" {{ old('uf', $supplier->uf) == 'AL' ? 'selected' : '' }}>Alagoas</option>
                            <option value="AP" {{ old('uf', $supplier->uf) == 'AP' ? 'selected' : '' }}>Amapá</option>
                            <option value="AM" {{ old('uf', $supplier->uf) == 'AM' ? 'selected' : '' }}>Amazonas</option>
                            <option value="BA" {{ old('uf', $supplier->uf) == 'BA' ? 'selected' : '' }}>Bahia</option>
                            <option value="CE" {{ old('uf', $supplier->uf) == 'CE' ? 'selected' : '' }}>Ceará</option>
                            <option value="DF" {{ old('uf', $supplier->uf) == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                            <option value="ES" {{ old('uf', $supplier->uf) == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                            <option value="GO" {{ old('uf', $supplier->uf) == 'GO' ? 'selected' : '' }}>Goiás</option>
                            <option value="MA" {{ old('uf', $supplier->uf) == 'MA' ? 'selected' : '' }}>Maranhão</option>
                            <option value="MT" {{ old('uf', $supplier->uf) == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                            <option value="MS" {{ old('uf', $supplier->uf) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                            <option value="MG" {{ old('uf', $supplier->uf) == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                            <option value="PA" {{ old('uf', $supplier->uf) == 'PA' ? 'selected' : '' }}>Pará</option>
                            <option value="PB" {{ old('uf', $supplier->uf) == 'PB' ? 'selected' : '' }}>Paraíba</option>
                            <option value="PR" {{ old('uf', $supplier->uf) == 'PR' ? 'selected' : '' }}>Paraná</option>
                            <option value="PE" {{ old('uf', $supplier->uf) == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                            <option value="PI" {{ old('uf', $supplier->uf) == 'PI' ? 'selected' : '' }}>Piauí</option>
                            <option value="RJ" {{ old('uf', $supplier->uf) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                            <option value="RN" {{ old('uf', $supplier->uf) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                            <option value="RS" {{ old('uf', $supplier->uf) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                            <option value="RO" {{ old('uf', $supplier->uf) == 'RO' ? 'selected' : '' }}>Rondônia</option>
                            <option value="RR" {{ old('uf', $supplier->uf) == 'RR' ? 'selected' : '' }}>Roraima</option>
                            <option value="SC" {{ old('uf', $supplier->uf) == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                            <option value="SP" {{ old('uf', $supplier->uf) == 'SP' ? 'selected' : '' }}>São Paulo</option>
                            <option value="SE" {{ old('uf', $supplier->uf) == 'SE' ? 'selected' : '' }}>Sergipe</option>
                            <option value="TO" {{ old('uf', $supplier->uf) == 'TO' ? 'selected' : '' }}>Tocantins</option>
                        </select>
                        @error('uf')
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
        // Máscaras dos campos
        $('#documento').mask('00.000.000/0000-00');
        $('#phone').mask('(00) 00000-0000');
        $('#cep').mask('00000-000');

        // Atualiza a máscara quando o tipo de pessoa muda
        $('#tipo_pessoa').change(function() {
            var tipo = $(this).val();
            var doc = $('#documento');
            doc.val(''); // Limpa o campo
            if (tipo === 'F') {
                doc.mask('000.000.000-00');
            } else {
                doc.mask('00.000.000/0000-00');
            }
        });

        // Define a máscara inicial baseada no tipo de pessoa
        var tipoPessoa = $('#tipo_pessoa').val();
        if (tipoPessoa === 'F') {
            $('#documento').mask('000.000.000-00');
        } else {
            $('#documento').mask('00.000.000/0000-00');
        }

        // Busca CEP
        $('#cep').blur(function() {
            var cep = $(this).val().replace(/\D/g, '');
            
            if (cep != "") {
                var validacep = /^[0-9]{8}$/;
                
                if(validacep.test(cep)) {
                    $('#rua').val('...');
                    $('#bairro').val('...');
                    $('#cidade').val('...');
                    $('#uf').val('...');

                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                        if (!("erro" in dados)) {
                            $('#rua').val(dados.logradouro);
                            $('#bairro').val(dados.bairro);
                            $('#cidade').val(dados.localidade);
                            $('#uf').val(dados.uf);
                        } else {
                            limpa_formulario_cep();
                            alert("CEP não encontrado.");
                        }
                    });
                } else {
                    limpa_formulario_cep();
                    alert("Formato de CEP inválido.");
                }
            } else {
                limpa_formulario_cep();
            }
        });

        function limpa_formulario_cep() {
            $('#rua').val('');
            $('#bairro').val('');
            $('#cidade').val('');
            $('#uf').val('');
        }
    });
</script>
@endpush
@endsection
