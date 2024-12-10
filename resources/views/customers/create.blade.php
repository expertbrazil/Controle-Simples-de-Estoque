@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-person-plus"></i> Novo Cliente
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

        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nome do Cliente</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" class="form-control cpf @error('cpf') is-invalid @enderror" 
                           id="cpf" name="cpf" value="{{ old('cpf') }}" data-mask="000.000.000-00">
                    @error('cpf')
                        <div class="invalid-feedback" id="cpf-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="phone" class="form-label">Telefone</label>
                    <input type="text" class="form-control phone @error('phone') is-invalid @enderror" 
                           id="phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="birth_date" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                           id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                    @error('birth_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" class="form-check-input @error('active') is-invalid @enderror" 
                               id="active" name="active" value="1" {{ old('active', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Cliente Ativo</label>
                        @error('active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Endereço</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                           id="address" name="address" value="{{ old('address') }}">
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2 mb-3">
                    <label for="number" class="form-label">Número</label>
                    <input type="text" class="form-control @error('number') is-invalid @enderror" 
                           id="number" name="number" value="{{ old('number') }}">
                    @error('number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="complement" class="form-label">Complemento</label>
                    <input type="text" class="form-control @error('complement') is-invalid @enderror" 
                           id="complement" name="complement" value="{{ old('complement') }}">
                    @error('complement')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="neighborhood" class="form-label">Bairro</label>
                    <input type="text" class="form-control @error('neighborhood') is-invalid @enderror" 
                           id="neighborhood" name="neighborhood" value="{{ old('neighborhood') }}">
                    @error('neighborhood')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="city" class="form-label">Cidade</label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror" 
                           id="city" name="city" value="{{ old('city') }}">
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="state" class="form-label">Estado</label>
                    <select class="form-select @error('state') is-invalid @enderror" id="state" name="state">
                        <option value="">Selecione...</option>
                        <option value="AC" {{ old('state') == 'AC' ? 'selected' : '' }}>Acre</option>
                        <option value="AL" {{ old('state') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                        <option value="AP" {{ old('state') == 'AP' ? 'selected' : '' }}>Amapá</option>
                        <option value="AM" {{ old('state') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                        <option value="BA" {{ old('state') == 'BA' ? 'selected' : '' }}>Bahia</option>
                        <option value="CE" {{ old('state') == 'CE' ? 'selected' : '' }}>Ceará</option>
                        <option value="DF" {{ old('state') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                        <option value="ES" {{ old('state') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                        <option value="GO" {{ old('state') == 'GO' ? 'selected' : '' }}>Goiás</option>
                        <option value="MA" {{ old('state') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                        <option value="MT" {{ old('state') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                        <option value="MS" {{ old('state') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                        <option value="MG" {{ old('state') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                        <option value="PA" {{ old('state') == 'PA' ? 'selected' : '' }}>Pará</option>
                        <option value="PB" {{ old('state') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                        <option value="PR" {{ old('state') == 'PR' ? 'selected' : '' }}>Paraná</option>
                        <option value="PE" {{ old('state') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                        <option value="PI" {{ old('state') == 'PI' ? 'selected' : '' }}>Piauí</option>
                        <option value="RJ" {{ old('state') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                        <option value="RN" {{ old('state') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                        <option value="RS" {{ old('state') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                        <option value="RO" {{ old('state') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                        <option value="RR" {{ old('state') == 'RR' ? 'selected' : '' }}>Roraima</option>
                        <option value="SC" {{ old('state') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                        <option value="SP" {{ old('state') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                        <option value="SE" {{ old('state') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                        <option value="TO" {{ old('state') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                    </select>
                    @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" class="form-control cep @error('cep') is-invalid @enderror" 
                           id="cep" name="cep" value="{{ old('cep') }}" data-mask="00000-000">
                    @error('cep')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('customers.index') }}" class="btn btn-light me-md-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Cliente</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.phone').mask('(00) 00000-0000');
        $('.cpf').mask('000.000.000-00');
        $('.cep').mask('00000-000');

        $('#cpf').on('blur', function() {
            validarCPF($(this).val());
        });
    });

    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g,'');
        if(cpf == '') return false;
        
        // Elimina CPFs invalidos conhecidos
        if (cpf.length != 11 || 
            cpf == "00000000000" || 
            cpf == "11111111111" || 
            cpf == "22222222222" || 
            cpf == "33333333333" || 
            cpf == "44444444444" || 
            cpf == "55555555555" || 
            cpf == "66666666666" || 
            cpf == "77777777777" || 
            cpf == "88888888888" || 
            cpf == "99999999999") {
                $('#cpf').addClass('is-invalid');
                $('#cpf-feedback').text('CPF inválido').show();
                return false;
            }
        
        // Valida 1o digito	
        add = 0;
        for (i=0; i < 9; i ++)
            add += parseInt(cpf.charAt(i)) * (10 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(9))) {
            $('#cpf').addClass('is-invalid');
            $('#cpf-feedback').text('CPF inválido').show();
            return false;
        }
        
        // Valida 2o digito	
        add = 0;
        for (i = 0; i < 10; i ++)
            add += parseInt(cpf.charAt(i)) * (11 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(10))) {
            $('#cpf').addClass('is-invalid');
            $('#cpf-feedback').text('CPF inválido').show();
            return false;
        }
        
        $('#cpf').removeClass('is-invalid').addClass('is-valid');
        $('#cpf-feedback').hide();
        return true;
    }

    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $("#address").val("");
        $("#neighborhood").val("");
        $("#city").val("");
        $("#state").val("");
    }

    function pesquisacep(valor) {
        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {
                //Preenche os campos com "..." enquanto consulta webservice.
                $("#address").val("...");
                $("#neighborhood").val("...");
                $("#city").val("...");
                $("#state").val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#address").val(dados.logradouro);
                        $("#neighborhood").val(dados.bairro);
                        $("#city").val(dados.localidade);
                        $("#state").val(dados.uf);
                    } else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            } else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    }

    $('#cep').on('blur', function() {
        pesquisacep($(this).val());
    });
</script>
@endsection
