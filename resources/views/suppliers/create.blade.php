@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <h2 class="mt-4">Novo Registro</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Cliente / Fornecedor / Revendedor</a></li>
            <li class="breadcrumb-item active">Novo Registro</li>
        </ol>
    </div>

    @include('layouts.messages')

    <div class="card">
        <div class="card-header">
            Cadastro de Cliente / Fornecedor / Revendedor
        </div>
        <div class="card-body">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="tipo_pessoa" class="form-label">Tipo de Pessoa</label>
                        <select class="form-select @error('tipo_pessoa') is-invalid @enderror" id="tipo_pessoa" name="tipo_pessoa">
                            <option value="F" {{ old('tipo_pessoa') == 'F' ? 'selected' : '' }}>Pessoa Física</option>
                            <option value="J" {{ old('tipo_pessoa') == 'J' ? 'selected' : '' }}>Pessoa Jurídica</option>
                        </select>
                        @error('tipo_pessoa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3 pessoa-fisica">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}">
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3 pessoa-juridica">
                        <label for="razao_social" class="form-label">Razão Social</label>
                        <input type="text" class="form-control @error('razao_social') is-invalid @enderror" id="razao_social" name="razao_social" value="{{ old('razao_social') }}">
                        @error('razao_social')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="documento" class="form-label">CPF/CNPJ</label>
                        <input type="text" class="form-control @error('documento') is-invalid @enderror" id="documento" name="documento" value="{{ old('documento') }}">
                        @error('documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="whatsapp" class="form-label">WhatsApp</label>
                        <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}">
                        @error('whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" value="{{ old('cep') }}">
                        @error('cep')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="rua" class="form-label">Rua</label>
                        <input type="text" class="form-control @error('rua') is-invalid @enderror" id="rua" name="rua" value="{{ old('rua') }}">
                        @error('rua')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero') }}">
                        @error('numero')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control @error('complemento') is-invalid @enderror" id="complemento" name="complemento" value="{{ old('complemento') }}">
                        @error('complemento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ old('bairro') }}">
                        @error('bairro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ old('cidade') }}">
                        @error('cidade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="uf" class="form-label">UF</label>
                        <input type="text" class="form-control @error('uf') is-invalid @enderror" id="uf" name="uf" value="{{ old('uf') }}" maxlength="2">
                        @error('uf')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="nome_contato" class="form-label">Nome do Contato</label>
                        <input type="text" class="form-control @error('nome_contato') is-invalid @enderror" id="nome_contato" name="nome_contato" value="{{ old('nome_contato') }}">
                        @error('nome_contato')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label d-block">Flag</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('flag') is-invalid @enderror" type="checkbox" name="flag[]" id="flag_cliente" value="cliente" {{ is_array(old('flag')) && in_array('cliente', old('flag')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="flag_cliente">Cliente</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('flag') is-invalid @enderror" type="checkbox" name="flag[]" id="flag_fornecedor" value="fornecedor" {{ is_array(old('flag')) && in_array('fornecedor', old('flag')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="flag_fornecedor">Fornecedor</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('flag') is-invalid @enderror" type="checkbox" name="flag[]" id="flag_revendedor" value="revendedor" {{ is_array(old('flag')) && in_array('revendedor', old('flag')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="flag_revendedor">Revendedor</label>
                        </div>
                        @error('flag')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3 user-credentials" style="display: none;">
                        <label for="usuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" value="{{ old('usuario') }}">
                        @error('usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                
                    <div class="col-md-6 mb-3 user-credentials" style="display: none;">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('senha') is-invalid @enderror" id="senha" name="senha">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            @error('senha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
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
            
            // Remove máscaras anteriores
            $documento.unmask();
            
            // Aplica a máscara adequada
            if (tipoPessoa === 'F') {
                $documento.mask('000.000.000-00');
                $documento.attr('placeholder', '000.000.000-00');
                // Mostra campos de pessoa física e esconde de pessoa jurídica
                $('.pessoa-fisica').show();
                $('.pessoa-juridica').hide();
                // Limpa o campo de razão social
                $('#razao_social').val('');
            } else {
                $documento.mask('00.000.000/0000-00');
                $documento.attr('placeholder', '00.000.000/0000-00');
                // Mostra campos de pessoa jurídica e esconde de pessoa física
                $('.pessoa-juridica').show();
                $('.pessoa-fisica').hide();
                // Limpa o campo de nome
                $('#nome').val('');
            }
        }

        // Atualiza a máscara quando o tipo de pessoa muda
        $('#tipo_pessoa').on('change', function() {
            var tipoPessoa = $(this).val();
            var oldTipoPessoa = $(this).data('old-value');
            
            // Se está mudando de tipo de pessoa e já tem dados preenchidos
            if (oldTipoPessoa && oldTipoPessoa !== tipoPessoa) {
                var temDados = (tipoPessoa === 'F' && $('#razao_social').val()) || 
                              (tipoPessoa === 'J' && $('#nome').val());
                
                if (temDados) {
                    if (!confirm('Ao mudar o tipo de pessoa, os dados do nome/razão social serão perdidos. Deseja continuar?')) {
                        $(this).val(oldTipoPessoa);
                        return;
                    }
                }
            }
            
            // Armazena o valor atual para a próxima verificação
            $(this).data('old-value', tipoPessoa);
            
            updateDocumentoMask();
        });
        
        // Aplica a máscara inicial e armazena o valor inicial
        $('#tipo_pessoa').data('old-value', $('#tipo_pessoa').val());
        updateDocumentoMask();

        // Máscara para telefone
        $('#phone').mask('(00) 0000-0000');
        
        // Máscara para WhatsApp
        $('#whatsapp').mask('(00) 00000-0000');
        
        // Máscara para CEP
        $('#cep').mask('00000-000');

        // Toggle senha
        $('#togglePassword').on('click', function() {
            var senha = $('#senha');
            if (senha.attr('type') === 'password') {
                senha.attr('type', 'text');
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                senha.attr('type', 'password');
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Função para controlar a visibilidade dos campos de usuário e senha
        function updateCredentialsVisibility() {
            var showCredentials = false;
            $('input[name="flag[]"]:checked').each(function() {
                var value = $(this).val();
                if (value === 'cliente' || value === 'revendedor') {
                    showCredentials = true;
                    return false; // break the loop
                }
            });

            if (showCredentials) {
                $('.user-credentials').slideDown();
            } else {
                $('.user-credentials').slideUp();
                // Limpa os campos quando esconde
                $('#usuario').val('');
                $('#senha').val('');
            }
        }

        // Atualiza visibilidade quando as flags mudam
        $('input[name="flag[]"]').on('change', updateCredentialsVisibility);

        // Executa na carga inicial da página
        updateCredentialsVisibility();
    });
</script>
@endpush

@endsection
