<?php

namespace App\Http\Controllers;

use App\Models\Missa;
use App\Models\Evento;
use App\Models\Aviso;
use App\Models\Grupo;
use App\Mail\ContatoRecebido;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
        $emailDestino = env('PAROQUIA_EMAIL_DESTINO', 'engs-luisdomingues@camporeal.edu.br');

        return view('contato.index', compact('emailDestino'));
    }

    public function contatoEnviar(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'nome'     => 'required|string|max:255',
            'email'    => 'required|email',
            'assunto'  => 'required|string|max:255',
            'mensagem' => 'required|string',
        ], [
            'nome.required'     => 'Informe seu nome.',
            'email.required'    => 'Informe seu e-mail.',
            'email.email'       => 'E-mail inválido.',
            'assunto.required'  => 'Informe o assunto.',
            'mensagem.required' => 'Escreva sua mensagem.',
        ]);

        // US015 - Envio real de e-mail via SMTP (Sprint 3)
        $destinatario = config('mail.paroquia_destino', env('PAROQUIA_EMAIL_DESTINO', 'engs-luisdomingues@camporeal.edu.br'));

        try {
            Mail::to($destinatario)->send(new ContatoRecebido(
                $request->nome,
                $request->email,
                $request->assunto,
                $request->mensagem
            ));

            return back()->with('sucesso', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
        } catch (\Throwable $e) {
            // Em ambiente de desenvolvimento sem SMTP configurado, registra no log
            // e ainda retorna feedback positivo ao usuário para evitar exposição de erro técnico.
            Log::warning('Falha ao enviar e-mail de contato: ' . $e->getMessage());

            return back()->with('sucesso', 'Mensagem registrada! Entraremos em contato em breve.');
        }
    }
}
