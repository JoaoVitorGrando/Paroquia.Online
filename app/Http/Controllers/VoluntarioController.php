<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoluntarioController extends Controller
{
    // US008 - Inscrever como voluntário em um evento
    public function inscrever(Request $request, $eventoId)
    {
        $request->validate([
            'mensagem' => 'nullable|string|max:500',
        ]);

        $evento = Evento::findOrFail($eventoId);
        $user   = Auth::user();

        // O administrador gerencia e acompanha, nao participa
        if ($user->is_admin) {
            return back()->with('erro', 'Administradores não podem se candidatar a voluntário. Use o painel para visualizar os voluntários.');
        }

        if ($user->eventosVoluntario()->where('evento_id', $evento->id)->exists()) {
            return back()->with('erro', 'Você já está inscrito como voluntário neste evento.');
        }

        $user->eventosVoluntario()->attach($evento->id, [
            'mensagem' => $request->mensagem,
        ]);

        return back()->with('sucesso', 'Inscrição como voluntário confirmada no evento "' . $evento->titulo . '"!');
    }

    // US008 - Cancelar inscrição de voluntário
    public function cancelar($eventoId)
    {
        $evento = Evento::findOrFail($eventoId);
        Auth::user()->eventosVoluntario()->detach($evento->id);

        return back()->with('sucesso', 'Inscrição de voluntário cancelada.');
    }
}
