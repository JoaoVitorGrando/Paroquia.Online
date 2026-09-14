<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;

class GrupoController extends Controller
{
    // US007 - Visualizar grupos da igreja
    public function index()
    {
        $grupos = Grupo::where('ativo', true)->get();

        $gruposInscritos = [];
        if (Auth::check()) {
            $gruposInscritos = Auth::user()->grupos->pluck('id')->toArray();
        }

        return view('grupos.index', compact('grupos', 'gruposInscritos'));
    }

    // Página informativa do Grupo de Dança Folclórica (pública, sem login)
    public function danca()
    {
        // Busca os dados do grupo (dia, horário, local) para manter tudo consistente
        $grupo = Grupo::where('nome', 'like', '%Dança%')->first();

        // Fotos do grupo: só os arquivos "imagem*" (a logo do grupo fica de fora da galeria)
        $fotos = collect(glob(public_path('images/imagens-grupo-danca/imagem*.{png,jpg,jpeg,webp}'), GLOB_BRACE))
            ->map(fn ($caminho) => 'images/imagens-grupo-danca/' . basename($caminho))
            ->values();

        return view('grupos.danca', compact('grupo', 'fotos'));
    }

    // US005 - Inscrever-se em um grupo
    public function inscrever($id)
    {
        $grupo = Grupo::findOrFail($id);
        $user  = Auth::user();

        // O administrador gerencia e acompanha, nao participa
        if ($user->is_admin) {
            return back()->with('erro', 'Administradores não podem se inscrever em grupos. Use o painel para visualizar os inscritos.');
        }

        if ($user->grupos()->where('grupo_id', $grupo->id)->exists()) {
            return back()->with('erro', 'Você já está inscrito neste grupo.');
        }

        $user->grupos()->attach($grupo->id);

        return back()->with('sucesso', 'Inscrição confirmada no grupo "' . $grupo->nome . '"!');
    }

    // US005 - Cancelar inscrição em um grupo
    public function cancelar($id)
    {
        $grupo = Grupo::findOrFail($id);
        Auth::user()->grupos()->detach($grupo->id);

        return back()->with('sucesso', 'Inscrição cancelada no grupo "' . $grupo->nome . '".');
    }
}
