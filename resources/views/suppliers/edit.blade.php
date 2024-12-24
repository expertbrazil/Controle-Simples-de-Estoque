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

    <div class="card">
        <div class="card-header">
            Edição de Fornecedor
        </div>
        <div class="card-body">
            <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">

                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="1" {{ old('status', $supplier->status) ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ !old('status', $supplier->status) ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                

                    <div class="col-12 col-md-4 mb-3">
                        <label for="tipo_pessoa" class="form-label">Tipo de Pessoa</label>
                        <select class="form-select @error('tipo_pessoa') is-invalid @enderror" id="tipo_pessoa" name="tipo_pessoa">
                            <option value="F" {{ old('tipo_pessoa', $supplier->tipo_pessoa) == 'F' ? 'selected' : '' }}>Pessoa Física</option>
                            <option value="J" {{ old('tipo_pessoa', $supplier->tipo_pessoa) == 'J' ? 'selected' : '' }}>Pessoa Jurídica</option>
                        </select>
                        @error('tipo_pessoa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 mb-3 pessoa-fisica">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome', $supplier->nome) }}">
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6 mb-3 pessoa-juridica">
                        <label for="razao_social" class="form-label">Razão Social</label>
                        <input type="text" class="form-control @error('razao_social') is-invalid @enderror" id="razao_social" name="razao_social" value="{{ old('razao_social', $supplier->razao_social) }}">
                        @error('razao_social')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="documento" class="form-label">CPF/CNPJ</label>
                        <input type="text" class="form-control @error('documento') is-invalid @enderror" id="documento" name="documento" value="{{ old('documento', $supplier->documento) }}">
                        @error('documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $supplier->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="whatsapp" class="form-label">WhatsApp</label>
                        <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $supplier->whatsapp) }}">
                        @error('whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome_contato" class="form-label">Nome do Contato</label>
                        <input type="text" class="form-control @error('nome_contato') is-invalid @enderror" 
                               id="nome_contato" 
                               name="nome_contato" 
                               value="{{ old('nome_contato', $supplier->nome_contato) }}">
                        @error('nome_contato')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="cep" class="form-label">CEP</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('cep') is-invalid @enderror" 
                                   id="cep" 
                                   name="cep" 
                                   value="{{ old('cep', $supplier->cep) }}">
                            <button class="btn btn-outline-secondary" type="button" id="buscarCep">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        @error('cep')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
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

                    <div class="col-md-2 mb-3">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control @error('complemento') is-invalid @enderror" id="complemento" name="complemento" value="{{ old('complemento', $supplier->complemento) }}">
                        @error('complemento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ old('bairro', $supplier->bairro) }}">
                        @error('bairro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ old('cidade', $supplier->cidade) }}">
                        @error('cidade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="uf" class="form-label">UF</label>
                        <input type="text" class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf" value="{{ old('uf', $supplier->uf) }}" maxlength="2">
                        @error('uf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label d-block">Flag</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('flag') is-invalid @enderror" 
                                   type="checkbox" 
                                   name="flag[]" 
                                   id="flag_cliente" 
                                   value="cliente" 
                                   {{ (is_array(old('flag', $supplier->flag)) && in_array('cliente', old('flag', $supplier->flag))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="flag_cliente">Cliente</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('flag') is-invalid @enderror" 
                                   type="checkbox" 
                                   name="flag[]" 
                                   id="flag_fornecedor" 
                                   value="fornecedor" 
                                   {{ (is_array(old('flag', $supplier->flag)) && in_array('fornecedor', old('flag', $supplier->flag))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="flag_fornecedor">Fornecedor</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('flag') is-invalid @enderror" 
                                   type="checkbox" 
                                   name="flag[]" 
                                   id="flag_revendedor" 
                                   value="revendedor" 
                                   {{ (is_array(old('flag', $supplier->flag)) && in_array('revendedor', old('flag', $supplier->flag))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="flag_revendedor">Revendedor</label>
                        </div>
                        @error('flag')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3 credentials-fields" style="display: none;">
                        <label for="usuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control @error('usuario') is-invalid @enderror" 
                               id="usuario" 
                               name="usuario" 
                               value="{{ old('usuario', $supplier->usuario) }}">
                        @error('usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3 credentials-fields" style="display: none;">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control @error('senha') is-invalid @enderror" 
                               id="senha" 
                               name="senha" 
                               value="{{ old('senha', $supplier->senha) }}">
                        @error('senha')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        // Função para atualizar a máscara do documento
        function updateDocumentoMask() {
            var tipoPessoa = $('#tipo_pessoa').val();
            var $documento = $('#documento');
            
            if (tipoPessoa === 'F') {
                $documento.mask('000.000.000-00');
            } else {
                $documento.mask('00.000.000/0000-00');
            }
        }

        // Função para verificar se os campos de credenciais devem ser mostrados
        function updateCredentialsFields() {
            var hasClienteOrRevendedor = false;
            $('input[name="flag[]"]:checked').each(function() {
                var value = $(this).val();
                if (value === 'cliente' || value === 'revendedor') {
                    hasClienteOrRevendedor = true;
                }
            });

            if (hasClienteOrRevendedor) {
                $('.credentials-fields').slideDown();
                $('#usuario, #senha').prop('required', true);
            } else {
                $('.credentials-fields').slideUp();
                $('#usuario, #senha').prop('required', false);
            }
        }

        // Inicialização
        updateDocumentoMask();
        updateCredentialsFields();

        // Event listeners
        $('#tipo_pessoa').on('change', updateDocumentoMask);
        
        $('input[name="flag[]"]').on('change', function() {
            updateCredentialsFields();
        });

        // Função para buscar endereço pelo CEP
        function buscarCep() {
            var cep = $('#cep').val().replace(/\D/g, '');
            
            if (cep.length !== 8) {
                alert('CEP inválido');
                return;
            }

            // Mostrar indicador de carregamento
            $('#buscarCep').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $('#buscarCep').prop('disabled', true);

            $.get(`https://viacep.com.br/ws/${cep}/json/`)
                .done(function(data) {
                    if (!data.erro) {
                        $('#rua').val(data.logradouro);
                        $('#bairro').val(data.bairro);
                        $('#cidade').val(data.localidade);
                        $('#uf').val(data.uf);
                        
                        // Foca no campo número após preencher o endereço
                        $('#numero').focus();
                    } else {
                        alert('CEP não encontrado');
                    }
                })
                .fail(function() {
                    alert('Erro ao buscar CEP. Tente novamente.');
                })
                .always(function() {
                    // Restaurar botão de busca
                    $('#buscarCep').html('<i class="bi bi-search"></i>');
                    $('#buscarCep').prop('disabled', false);
                });
        }

        // Event listener para o botão de buscar CEP
        $('#buscarCep').on('click', buscarCep);

        // Buscar CEP ao pressionar Enter no campo
        $('#cep').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                buscarCep();
            }
        });

        // Máscaras para os campos
        $('#phone').mask('(00) 0000-0000');
        $('#whatsapp').mask('(00) 00000-0000');
        $('#cep').mask('00000-000');
    });
</script>
@endpush
