<!DOCTYPE html>
<html lang="pt-BR" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Controle de Estoque') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            padding: 0.5rem 0;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
        }

        /* Main Content */
        .main-content {
            flex: 1 0 auto;
            margin-top: 4rem;
            margin-bottom: 4rem;
            padding: 1.5rem 0;
        }

        /* Footer */
        .footer {
            background: #f8f9fa;
            padding: 1rem 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            border-top: 1px solid #dee2e6;
        }

        /* Navbar */
        .navbar {
            padding: 0;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
            font-size: 1.4rem;
            padding: 0.5rem 0;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.7rem 1rem !important;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.7%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Dropdowns */
        .dropdown-menu {
            margin-top: 0.5rem !important;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            padding: 0.5rem;
        }

        .dropdown-item {
            padding: 0.7rem 1rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }

        .dropdown-item.active {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Forms */
        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .navbar-collapse {
                background-color: #0a58ca;
                margin-top: 0.5rem;
                padding: 1rem;
                border-radius: 0.5rem;
            }

            .dropdown-menu {
                background-color: rgba(255, 255, 255, 0.1);
                border: none;
                box-shadow: none;
            }

            .dropdown-item {
                color: rgba(255, 255, 255, 0.9);
            }

            .dropdown-item:hover {
                background-color: rgba(255, 255, 255, 0.2);
                color: white;
            }

            .main-content {
                margin-top: 5rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        <!-- Header -->
        <header class="header">
            <nav class="navbar navbar-expand-md">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                        <i class="fas fa-boxes me-2"></i>
                        {{ config('app.name', 'Controle de Estoque') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            @auth
                                <!-- Dashboard -->
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                        <i class="fas fa-home"></i>
                                        Dashboard
                                    </a>
                                </li>

                            <!-- PDV -->
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('pdv.*') ? 'active' : '' }}" href="{{ route('pdv.index') }}">
                                        <i class="bi bi-cart"></i>
                                        PDV
                                    </a>
                                </li>

                                <!-- Produtos e Estoque -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle {{ Request::routeIs(['products.*', 'product-entries.*', 'price-lists.*', 'price-histories.*']) ? 'active' : '' }}" 
                                       href="#" data-bs-toggle="dropdown">
                                        <i class="fas fa-boxes"></i>
                                        Produtos e Estoque
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item {{ Request::routeIs('products.*') ? 'active' : '' }}" 
                                               href="{{ route('products.index') }}">
                                                <i class="fas fa-box"></i>
                                                Produtos
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ Request::routeIs('product-entries.*') ? 'active' : '' }}" 
                                               href="{{ route('product-entries.index') }}">
                                                <i class="fas fa-truck-loading"></i>
                                                Entradas
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ Request::routeIs('price-lists.*') ? 'active' : '' }}" 
                                               href="{{ route('price-lists.index') }}">
                                                <i class="fas fa-tags"></i>
                                                Listas de Preços
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ Request::routeIs('price-histories.*') ? 'active' : '' }}" 
                                               href="{{ route('price-histories.index') }}">
                                                <i class="fas fa-history"></i>
                                                Histórico de Preços
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Cadastros -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle {{ Request::routeIs(['categories.*', 'brands.*', 'suppliers.*']) ? 'active' : '' }}" 
                                       href="#" data-bs-toggle="dropdown">
                                        <i class="fas fa-folder"></i>
                                        Cadastros
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item {{ Request::routeIs('categories.*') ? 'active' : '' }}" 
                                               href="{{ route('categories.index') }}">
                                                <i class="fas fa-tags"></i>
                                                Categorias
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ Request::routeIs('brands.*') ? 'active' : '' }}" 
                                               href="{{ route('brands.index') }}">
                                                <i class="fas fa-trademark"></i>
                                                Marcas
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ Request::routeIs('suppliers.*') ? 'active' : '' }}" 
                                               href="{{ route('suppliers.index') }}">
                                                <i class="fas fa-truck"></i>
                                                Fornecedores
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Configurações -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle {{ Request::routeIs('parameters.*') ? 'active' : '' }}" 
                                       href="#" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog"></i>
                                        Configurações
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item {{ Request::routeIs('parameters.*') ? 'active' : '' }}" 
                                               href="{{ route('parameters.index') }}">
                                                <i class="fas fa-sliders-h"></i>
                                                Parâmetros
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endauth
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">
                                            <i class="fas fa-sign-in-alt"></i>
                                            {{ __('Login') }}
                                        </a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">
                                            <i class="fas fa-user-plus"></i>
                                            {{ __('Register') }}
                                        </a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                        <i class="fas fa-user-circle"></i>
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('profile') }}">
                                                <i class="fas fa-user"></i>
                                                Perfil
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fas fa-sign-out-alt"></i>
                                                {{ __('Logout') }}
                                            </a>
                                        </li>
                                    </ul>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        &copy; {{ date('Y') }} {{ config('app.name', 'Controle de Estoque') }}. Todos os direitos reservados.
                    </div>
                    <div>
                        <a href="#" class="text-decoration-none text-muted">Termos de Uso</a>
                        <span class="mx-2">|</span>
                        <a href="#" class="text-decoration-none text-muted">Política de Privacidade</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa todos os tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Adiciona a classe active ao item do menu atual
            var currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(function(link) {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
