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

            <h5 class="mt-4 mb-3">Endereço</h5>
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

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="rua" class="form-label">Endereço</label>
                    <input type="text" class="form-control @error('rua') is-invalid @enderror" 
                           id="rua" name="rua" value="{{ old('rua') }}">
                    @error('rua')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="number" class="form-label">Número</label>
                    <input type="text" class="form-control @error('number') is-invalid @enderror" 
                           id="number" name="number" value="{{ old('number') }}">
                    @error('number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" class="form-control @error('bairro') is-invalid @enderror" 
                           id="bairro" name="bairro" value="{{ old('bairro') }}">
                    @error('bairro')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" class="form-control @error('cidade') is-invalid @enderror" 
                           id="cidade" name="cidade" value="{{ old('cidade') }}">
                    @error('cidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="uf" class="form-label">Estado</label>
                    <select class="form-select @error('uf') is-invalid @enderror" id="uf" name="uf">
                        <option value="">Selecione...</option>
                        <option value="AC" {{ old('uf') == 'AC' ? 'selected' : '' }}>Acre</option>
                        <option value="AL" {{ old('uf') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                        <option value="AP" {{ old('uf') == 'AP' ? 'selected' : '' }}>Amapá</option>
                        <option value="AM" {{ old('uf') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                        <option value="BA" {{ old('uf') == 'BA' ? 'selected' : '' }}>Bahia</option>
                        <option value="CE" {{ old('uf') == 'CE' ? 'selected' : '' }}>Ceará</option>
                        <option value="DF" {{ old('uf') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                        <option value="ES" {{ old('uf') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                        <option value="GO" {{ old('uf') == 'GO' ? 'selected' : '' }}>Goiás</option>
                        <option value="MA" {{ old('uf') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                        <option value="MT" {{ old('uf') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                        <option value="MS" {{ old('uf') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                        <option value="MG" {{ old('uf') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                        <option value="PA" {{ old('uf') == 'PA' ? 'selected' : '' }}>Pará</option>
                        <option value="PB" {{ old('uf') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                        <option value="PR" {{ old('uf') == 'PR' ? 'selected' : '' }}>Paraná</option>
                        <option value="PE" {{ old('uf') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                        <option value="PI" {{ old('uf') == 'PI' ? 'selected' : '' }}>Piauí</option>
                        <option value="RJ" {{ old('uf') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                        <option value="RN" {{ old('uf') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                        <option value="RS" {{ old('uf') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                        <option value="RO" {{ old('uf') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                        <option value="RR" {{ old('uf') == 'RR' ? 'selected' : '' }}>Roraima</option>
                        <option value="SC" {{ old('uf') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                        <option value="SP" {{ old('uf') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                        <option value="SE" {{ old('uf') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                        <option value="TO" {{ old('uf') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                    </select>
                    @error('uf')
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

        function limpa_formulário_cep() {
            document.getElementById('rua').value = "";
            document.getElementById('bairro').value = "";
            document.getElementById('cidade').value = "";
            document.getElementById('uf').value = "";
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                document.getElementById('rua').value = conteudo.logradouro;
                document.getElementById('bairro').value = conteudo.bairro;
                document.getElementById('cidade').value = conteudo.localidade;
                document.getElementById('uf').value = conteudo.uf;
            } else {
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        }
        
        function pesquisacep(valor) {
            var cep = valor.replace(/\D/g, '');

            if (cep != "") {
                var validacep = /^[0-9]{8}$/;

                if(validacep.test(cep)) {
                    document.getElementById('rua').value = "...";
                    document.getElementById('bairro').value = "...";
                    document.getElementById('cidade').value = "...";
                    document.getElementById('uf').value = "...";

                    var script = document.createElement('script');
                    script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
                    document.body.appendChild(script);
                } else {
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } else {
                limpa_formulário_cep();
            }
        }

        document.getElementById('cep').addEventListener('blur', function() {
            pesquisacep(this.value);
        });
    });

    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g,'');
        if(cpf == '') return false;
        
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
</script>
@endsection
