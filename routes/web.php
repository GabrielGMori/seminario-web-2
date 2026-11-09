<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'loginSubmit')->name('loginSubmit');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerSubmit')->name('registerSubmit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/produtos', fn () => view('produtos.index'))->name('produtos.index');
    Route::get('/produtos/novo', fn () => view('produtos.form'))->name('produtos.create');
    Route::get('/produtos/{produto}/editar', fn ($produto) => view('produtos.form', compact('produto')))->name('produtos.edit');

    Route::get('/vendas/nova', fn () => view('movimentacoes.create', ['tipo' => 'venda']))->name('vendas.create');
    Route::get('/vendas', fn () => view('vendas.index'))->name('vendas.index');

    Route::get('/compras/nova', fn () => view('movimentacoes.create', ['tipo' => 'compra']))->name('compras.create');
    Route::get('/compras', fn () => view('compras.index'))->name('compras.index');

    Route::get('/categorias', fn () => view('categorias.index'))->name('categorias.index');
    
    Route::get('/relatorios', fn () => view('relatorios.index'))->name('relatorios.index');
});

Route::post('/lang', function (Request $request) {
    $validated = $request->validate([
        'locale' => 'required|in:pt_BR,en,es',
    ]);

    $request->session()->put('locale', $validated['locale']);

    return redirect()->back();
})->name('lang.switch');

Route::fallback(function() {
    if (auth()->check()) {
        redirect()->route('produtos.index');
    }
    return redirect()->route('login');
});
