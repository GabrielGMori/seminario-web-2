<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerSubmit(RegisterRequest $request)
    {
        User::create($request->validated());

        return redirect('login')->with('status', __('ui.user_registered'));
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginSubmit(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('produtos.index');
        }

        return back()->withErrors(['credentials' => __('ui.invalid_credentials')]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
