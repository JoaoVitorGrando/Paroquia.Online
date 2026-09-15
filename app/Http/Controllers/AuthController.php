<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Acesso administrativo.
 *
 * O site e informativo: visitantes nao criam conta. O unico login existente e o
 * do administrador da paroquia, criado pelo AdminSeeder a partir do .env.
 */
class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Informe seu e-mail.',
            'email.email'       => 'Informe um e-mail valido.',
            'password.required' => 'Informe sua senha.',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return back()->with('erro', 'E-mail ou senha incorretos.');
        }

        // Somente administradores tem acesso: qualquer outra conta e recusada.
        if (! Auth::user()->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->with('erro', 'Esta area e restrita a administradores.');
        }

        $request->session()->regenerate();

        return redirect()->route('admin.index')
            ->with('sucesso', 'Bem-vindo(a), ' . Auth::user()->name . '.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('sucesso', 'Voce saiu do painel.');
    }
}
