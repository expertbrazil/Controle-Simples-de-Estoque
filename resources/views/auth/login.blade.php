@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-card">
    <!-- Logo -->
    <div class="text-center mb-4">
        @if($systemLogo = \App\Models\Parameter::where('key', 'system_logo')->first()?->value)
            <img src="{{ asset('storage/' . $systemLogo) }}" alt="Logo" class="login-logo mb-3">
        @else
            <i class="bi bi-box-seam text-primary" style="font-size: 2rem;"></i>
        @endif
        <h4 class="mb-2">Bem-vindo de volta!</h4>
        <p class="text-secondary small">Faça login para continuar</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" 
                   placeholder="E-mail"
                   value="{{ old('email') }}" 
                   required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   name="password" 
                   placeholder="Senha"
                   required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Lembrar-me</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right"></i> Entrar
        </button>

        @if (Route::has('register'))
            <div class="text-center mt-4">
                <span class="text-secondary">Não tem uma conta?</span>
                <a href="{{ route('register') }}" class="text-primary text-decoration-none ms-1">Criar conta</a>
            </div>
        @endif
    </form>
</div>
@endsection

@push('styles')
<style>
body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #6c5ce7 0%, #a393eb 100%);
}

.login-card {
    background: white;
    padding: 2.5rem;
    border-radius: 1rem;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
}

.login-logo {
    height: 40px;
    width: auto;
}

.form-control {
    padding: 0.8rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #dee2e6;
}

.form-control:focus {
    border-color: #6c5ce7;
    box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.15);
}

.btn-primary {
    background: #6c5ce7;
    border: none;
    padding: 0.8rem;
    border-radius: 0.5rem;
    font-weight: 500;
}

.btn-primary:hover {
    background: #5b4cdb;
}

.form-check-input:checked {
    background-color: #6c5ce7;
    border-color: #6c5ce7;
}

.text-primary {
    color: #6c5ce7 !important;
}

@media (max-width: 576px) {
    .login-card {
        margin: 1rem;
        padding: 1.5rem;
    }
}
</style>
@endpush
