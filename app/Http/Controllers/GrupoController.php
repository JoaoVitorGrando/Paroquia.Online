<?php

namespace App\Http\Controllers;

use App\Models\Grupo;

class GrupoController extends Controller
{
    // US007 - Visualizar grupos da igreja
    public function index()
    {
        $grupos = Grupo::where('ativo', true)->orderBy('nome')->get();

        return view('grupos.index', compact('grupos'));
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
}
