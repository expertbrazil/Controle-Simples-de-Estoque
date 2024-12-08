@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Login</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus>
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Lembrar-me</label>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
        </form>
    </div>
    <div class="card-footer text-center">
        <p class="mb-0">Não tem uma conta? <a href="{{ route('register') }}">Registre-se</a></p>
    </div>
</div>
@endsection
