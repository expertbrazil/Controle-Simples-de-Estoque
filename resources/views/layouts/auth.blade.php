<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    @stack('styles')
</head>
<body class="auth-body">
    @if(session('success'))
        <div class="alert alert-success alert-floating">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-floating">
            {{ session('error') }}
        </div>
    @endif

    <div class="auth-container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <style>
        .auth-body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6c5ce7 0%, #a393eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 250px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</body>
</html>
