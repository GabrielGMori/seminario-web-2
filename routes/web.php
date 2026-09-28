<?php

use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::redirect('/', '/produtos');

Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'loginSubmit')->name('loginSubmit');
        Route::get('/register', 'register')->name('register');
        Route::post('/login', 'loginSubmit')->name('loginSubmit');
    });

    Route::middleware('auth')->post('/logout', 'logout')->name('logout');
});

Route::controller(ProdutoController::class)->middleware('auth')->prefix('/produtos')->name('produtos.')->group(function () {
    Route::get('/', 'index')->name('.index');
    Route::get('/novo', 'create')->name('create');
    Route::post('/novo', 'store')->name('store');
    Route::get('/editar', 'edit')->name('edit');
    Route::patch('/editar', 'update')->name('update');
    Route::delete('/remover', 'destroy')->name('destroy');
});
