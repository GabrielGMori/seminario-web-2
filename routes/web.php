<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/produtos');

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'loginSubmit')->name('loginSubmit');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerSubmit')->name('registerSubmit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
    Route::get('/produtos/novo', [ProdutoController::class, 'create'])->name('produtos.create');
    Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::get('/produtos/{produto}/editar', [ProdutoController::class, 'edit'])->name('produtos.edit');
    Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');
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
