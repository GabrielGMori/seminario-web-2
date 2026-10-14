<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerSubmit(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ], [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute deve ser um texto.',
            'email' => 'Informe um endereço de e-mail válido.',
            'max' => 'O campo :attribute não pode ter mais de :max caracteres.',
            'unique' => 'Este e-mail já está cadastrado.',
            'confirmed' => 'A confirmação da senha não corresponde.',
            'min' => 'A senha deve ter pelo menos :min caracteres.',
        ], [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
        ]);

        User::create($credentials);

        return redirect('login')->with('status', 'Usuário cadastrado com sucesso!');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginSubmit(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'required' => 'O campo :attribute é obrigatório.',
            'email' => 'Informe um endereço de e-mail válido.',
        ], [
            'email' => 'e-mail',
            'password' => 'senha',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('produtos.index');
        }

        return back()->withErrors(['credentials' => 'O e-mail ou a senha informados estão incorretos.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
