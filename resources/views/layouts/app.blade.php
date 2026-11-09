<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
                @auth
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="@lang('ui.open_menu')"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="menuPrincipal">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('produtos.*') ? 'active' : '' }}" href="{{ route('produtos.index') }}">@lang('navigation.products')</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('vendas.*') ? 'active' : '' }}" href="{{ route('vendas.index') }}">@lang('navigation.sales')</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('compras.*') ? 'active' : '' }}" href="{{ route('compras.index') }}">@lang('navigation.purchases')</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" href="{{ route('categorias.index') }}">@lang('navigation.categories')</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}" href="{{ route('relatorios.index') }}">@lang('navigation.reports')</a></li>
                        </ul>
                        <div class="d-flex align-items-center gap-2">
                            <x-language-switcher />
                            <span class="text-muted small d-none d-sm-inline">@lang('navigation.greeting', ['name' => auth()->user()->name])</span>
                            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn btn-outline-secondary btn-sm">@lang('navigation.logout')</button></form>
                        </div>
                    </div>
                @else
                    <div class="d-flex align-items-center gap-2">
                        <x-language-switcher />
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">@lang('navigation.login')</a>
                    </div>
                @endauth
            </div>
        </nav>
    </header>
    <main>@yield('content')</main>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
