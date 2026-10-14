<?php

use App\Http\Controllers\AuthController;
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

    Route::get('/produtos', fn () => view('produtos.index'))->name('produtos.index');
    Route::get('/produtos/novo', fn () => view('produtos.form'))->name('produtos.create');
    Route::get('/produtos/{produto}/editar', fn (int $produto) => view('produtos.form'))
        ->name('produtos.edit');
});
