<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // US003 - Exibe formulário de cadastro
    public function showCadastro()
    {
        return view('auth.cadastro');
    }

    // US003 - Processa o cadastro do usuário
    public function cadastrar(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
        ], [
            'name.required'         => 'O nome é obrigatório.',
            'email.required'        => 'O e-mail é obrigatório.',
            'email.email'           => 'Informe um e-mail válido.',
            'email.unique'          => 'Este e-mail já está cadastrado.',
            'password.required'     => 'A senha é obrigatória.',
            'password.min'          => 'A senha deve ter no mínimo 6 caracteres.',
            'password.confirmed'    => 'As senhas não coincidem.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('sucesso', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    // US004 - Exibe formulário de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // US004 - Processa o login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Informe seu e-mail.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'Informe sua senha.',
        ]);

        $credenciais = $request->only('email', 'password');

        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();

            $destino = Auth::user()->is_admin
                ? route('admin.index')
                : route('missas.index');

            return redirect($destino)->with('sucesso', 'Login realizado com sucesso! Bem-vindo(a), ' . Auth::user()->name . '.');
        }

        return back()->with('erro', 'E-mail ou senha incorretos.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('missas.index')->with('sucesso', 'Você saiu da sua conta.');
    }
}
