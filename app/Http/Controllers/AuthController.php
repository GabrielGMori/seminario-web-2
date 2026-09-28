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
            'name' => 'required|string',
            'email'=> 'required|email',
            'password'=> 'required|confirmed',
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
            'email'=> 'required|email',
            'password'=> 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('produtos.index');
        }

        return back()->withErrors(['credentials' => 'As credenciais inseridas estão incorretas']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
