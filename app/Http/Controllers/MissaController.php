<?php

namespace App\Http\Controllers;

use App\Models\Missa;

class MissaController extends Controller
{
    // US001 - Exibir horarios de missas (ordenacao centralizada no model)
    public function index()
    {
        $missas = Missa::listarOrdenadas()->where('ativo', true)->values();

        // US001 - Faixa "Proxima missa" no topo da pagina
        $proximaMissa = Missa::proxima();

        return view('missas.index', compact('missas', 'proximaMissa'));
    }
}
