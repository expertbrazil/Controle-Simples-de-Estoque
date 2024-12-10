<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Controle de Estoque') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @stack('styles')

    <style>
        body {
            padding-top: 70px; /* Espaço para o navbar fixo */
        }
        
        .navbar-nav .nav-link {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            transition: all 0.2s ease-in-out;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .navbar-nav .nav-link.active {
            background-color: var(--bs-primary);
            color: white !important;
        }

        .navbar-nav .nav-link i {
            margin-right: 0.5rem;
        }
    </style>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/imask"></script>
    <script src="{{ asset('js/pdv.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            $('.money').mask('#.###.###.##0,00', {
                reverse: true,
                translation: {
                    '#': {
                        pattern: /[0-9]/,
                        optional: true
                    }
                }
            });
            $('.phone').mask('(00) 00000-0000');
            $('.cpf').mask('000.000.000-00');
            $('.cep').mask('00000-000');
        });

        /* SETUP DOS IDS DO FORM */        
        var rua='#rua';
        var bairro='#bairro';
        var cidade='#cidade';
        var uf='#uf';
        var cep='#cep';
                            
        /* CODIGO BRUTO */                        
        $(document).ready(function() {
            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $(rua).val('');
                $(bairro).val('');
                $(cidade).val('');
                $(uf).val('');
            }
                    
            //Quando o campo cep perde o foco.
            $(cep).blur(function() {
                //Nova variável "cep" somente com dígitos.
                var cepx = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cepx !='') {
                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;
                    //Valida o formato do CEP.
                    if(validacep.test(cepx)) {
                        //Preenche os campos com "..." enquanto consulta webservice.
                        $(rua).val('...buscando informações');
                        $(bairro).val('...buscando informações');
                        $(cidade).val('...buscando informações');
                        $(uf).val('...buscando informações');

                        //Consulta o webservice viacep.com.br/
                        $.getJSON('https://viacep.com.br/ws/'+ cepx +'/json/?callback=?', function(dados) {
                            if (!('erro' in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $(rua).val(dados.logradouro);
                                $(bairro).val(dados.bairro);
                                $(cidade).val(dados.localidade);
                                $(uf).val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                alert('CEP não encontrado.');
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert('Formato de CEP inválido.');
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });
    </script>
    <script>
        // Verificar se o Bootstrap foi carregado
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded - Bootstrap:', typeof bootstrap !== 'undefined' ? 'Carregado' : 'Não carregado');
        });
    </script>
    @stack('scripts')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container-fluid px-4">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="bi bi-box-seam"></i> Controle de Estoque
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="bi bi-house"></i> Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pdv.*') ? 'active' : '' }}" href="{{ route('pdv.index') }}">
                                <i class="bi bi-cart"></i> PDV
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                <i class="bi bi-box-seam"></i> Produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                                <i class="bi bi-tags"></i> Categorias
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                                <i class="bi bi-people"></i> Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('parameters.*') ? 'active' : '' }}" href="{{ route('parameters.index') }}">
                                <i class="bi bi-gear"></i> Parâmetros
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if (session('success'))
                <div class="container-fluid px-4">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="container-fluid px-4">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        @if (session('success'))
        <script>
            Swal.fire({
                title: 'Sucesso!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
        @endif

        @if (session('error'))
        <script>
            Swal.fire({
                title: 'Erro!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
        @endif
    </div>
</body>
</html>
