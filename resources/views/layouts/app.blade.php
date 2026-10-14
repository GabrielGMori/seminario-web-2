<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LiveShop')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.8/dist/litera/bootstrap.min.css">
    @livewireStyles
</head>
<body class="bg-body-tertiary">
    <header>
        <nav class="navbar navbar-expand-lg bg-white border-bottom" data-bs-theme="light">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="{{ route('produtos.index') }}">LiveShop</a>
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <span class="text-muted small d-none d-sm-inline">Olá, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Entrar</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
