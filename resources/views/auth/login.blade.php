@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 480px;">
    <div class="card card-body shadow-sm">
        <h1 class="h3 mb-1">Entrar no LiveShop</h1>
        <p class="text-muted mb-4">Acesse sua conta para gerenciar os produtos.</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->has('credentials'))
            <div class="alert alert-danger">{{ $errors->first('credentials') }}</div>
        @endif

        <form method="POST" action="{{ route('loginSubmit') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror" required autofocus>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input id="password" type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary w-100" type="submit">Entrar</button>
        </form>
        <p class="text-center mt-3 mb-0">Ainda não tem conta? <a href="{{ route('register') }}">Cadastre-se</a></p>
    </div>
</div>
@endsection
