<?php

namespace App\Http\Controllers;

use App\Models\Missa;
use App\Models\Evento;
use App\Models\Aviso;
use App\Models\Grupo;

class HomeController extends Controller
{
    public function index()
    {
        $proximosEventos = Evento::where('data', '>=', now()->toDateString())
            ->orderBy('data', 'asc')
            ->take(3)
            ->get();

        // Vários avisos em destaque (antes só aparecia um)
        $avisosDestaque = Aviso::where('destaque', true)
            ->latest()
            ->take(3)
            ->get();

        // Grupos exibidos na seção "Nossos grupos e pastorais"
        $gruposDestaque = Grupo::where('ativo', true)
            ->orderBy('nome')
            ->take(3)
            ->get();

        // Faixa "Próxima missa" + agenda da semana (DOM a SÁB)
        $proximaMissa = Missa::proxima();

        $missasSemana = [];
        for ($i = 0; $i <= 6; $i++) {
            $missasSemana[$i] = collect();
        }

        foreach (Missa::listarOrdenadas() as $missa) {
            if (! $missa->ativo) {
                continue;
            }

            $indice = Missa::indiceDia($missa->dia_semana);

            if ($indice !== null) {
                $missasSemana[$indice]->push($missa);
            }
        }

        return view('home', compact(
            'proximosEventos', 'avisosDestaque', 'gruposDestaque', 'proximaMissa', 'missasSemana'
        ));
    }

    public function sobre()
    {
        return view('sobre');
    }

    // Página informativa da catequese
    public function catequese()
    {
        return view('catequese.index');
    }

    // Página informativa de batizados e casamentos
    public function sacramentos()
    {
        return view('sacramentos.index');
    }

    public function contato()
    {
        return view('contato.index');
    }
}
