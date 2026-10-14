@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 480px;">
    <div class="card card-body shadow-sm">
        <h1 class="h3 mb-1">Criar conta</h1>
        <p class="text-muted mb-4">Cadastre-se para acessar o LiveShop.</p>

        @if ($errors->any())
            <div class="alert alert-danger">Confira os campos destacados e tente novamente.</div>
        @endif

        <form method="POST" action="{{ route('registerSubmit') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input id="password" type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar senha</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Criar conta</button>
        </form>
        <p class="text-center mt-3 mb-0">Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
    </div>
</div>
@endsection
