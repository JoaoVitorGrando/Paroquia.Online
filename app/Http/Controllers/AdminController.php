<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Aviso;
use App\Models\Grupo;
use App\Models\Missa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ========================================
    // Helpers de imagem (grupos e eventos)
    // ========================================

    // Move o arquivo enviado para public/uploads/{pasta} e devolve o caminho relativo
    private function salvarImagem(Request $request, string $pasta): ?string
    {
        if (! $request->hasFile('imagem')) {
            return null;
        }

        $arquivo   = $request->file('imagem');
        $extensao  = strtolower($arquivo->getClientOriginalExtension() ?: 'jpg');
        $nome      = $pasta . '-' . uniqid() . '.' . $extensao;
        $destino   = public_path('uploads/' . $pasta);

        $arquivo->move($destino, $nome);

        return 'uploads/' . $pasta . '/' . $nome;
    }

    // Apaga do disco a imagem antiga, se existir
    private function removerImagem(?string $caminho): void
    {
        if (! $caminho) {
            return;
        }

        $arquivo = public_path($caminho);

        if (is_file($arquivo)) {
            @unlink($arquivo);
        }
    }

    // Regra de validacao usada nos formularios de grupo e evento
    private function regrasImagem(): array
    {
        return ['imagem' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'];
    }

    // ========================================
    // Painel administrativo (dashboard)
    // ========================================
    public function index()
    {
        $totalEventos = Evento::count();
        $totalAvisos  = Aviso::count();
        $totalGrupos  = Grupo::count();
        $totalMissas  = Missa::count();

        // Indicadores secundarios de cada cartao
        $eventosProximos  = Evento::where('data', '>=', now()->toDateString())->count();
        $avisosDestaque   = Aviso::where('destaque', true)->count();
        $missasAtivas     = Missa::where('ativo', true)->count();
        $totalInscritos   = Grupo::withCount('inscritos')->get()->sum('inscritos_count');

        // Paineis do dashboard
        $proximosEventos = Evento::where('data', '>=', now()->toDateString())
            ->orderBy('data', 'asc')
            ->take(3)
            ->get();

        $gruposPopulares = Grupo::withCount('inscritos')
            ->orderByDesc('inscritos_count')
            ->take(5)
            ->get();

        $avisosRecentes = Aviso::latest()->take(3)->get();

        return view('admin.index', compact(
            'totalEventos', 'totalAvisos', 'totalGrupos', 'totalMissas',
            'eventosProximos', 'avisosDestaque', 'missasAtivas', 'totalInscritos',
            'proximosEventos', 'gruposPopulares', 'avisosRecentes'
        ));
    }

    // US006 - Listar eventos no admin
    public function eventos()
    {
        $eventos = Evento::withCount('voluntarios')->orderBy('data', 'desc')->get();
        return view('admin.eventos.index', compact('eventos'));
    }

    // US006 - Formulário para criar evento
    public function criarEvento()
    {
        return view('admin.eventos.criar');
    }

    // US006 - Salvar novo evento
    public function salvarEvento(Request $request)
    {
        $request->validate(array_merge([
            'titulo'    => 'required|string|max:255',
            'descricao' => 'required|string',
            'data'      => 'required|date',
            'horario'   => 'nullable|date_format:H:i',
            'local'     => 'nullable|string|max:255',
        ], $this->regrasImagem()), [
            'titulo.required'    => 'O título é obrigatório.',
            'descricao.required' => 'A descrição é obrigatória.',
            'data.required'      => 'A data é obrigatória.',
            'data.date'          => 'Data inválida.',
        ]);

        $dados = $request->only('titulo', 'descricao', 'data', 'horario', 'local');

        // Foto do evento (opcional)
        if ($caminho = $this->salvarImagem($request, 'eventos')) {
            $dados['imagem'] = $caminho;
        }

        Evento::create($dados);

        return redirect()->route('admin.eventos')->with('sucesso', 'Evento cadastrado com sucesso!');
    }

    // US006 - Formulário para editar evento
    public function editarEvento($id)
    {
        $evento = Evento::findOrFail($id);
        return view('admin.eventos.editar', compact('evento'));
    }

    // US006 - Atualizar evento
    public function atualizarEvento(Request $request, $id)
    {
        $request->validate(array_merge([
            'titulo'    => 'required|string|max:255',
            'descricao' => 'required|string',
            'data'      => 'required|date',
            'horario'   => 'nullable|date_format:H:i',
            'local'     => 'nullable|string|max:255',
        ], $this->regrasImagem()));

        $evento = Evento::findOrFail($id);
        $dados  = $request->only('titulo', 'descricao', 'data', 'horario', 'local');

        // Foto nova substitui a antiga; checkbox remover_imagem apaga a atual
        if ($caminho = $this->salvarImagem($request, 'eventos')) {
            $this->removerImagem($evento->imagem);
            $dados['imagem'] = $caminho;
        } elseif ($request->has('remover_imagem')) {
            $this->removerImagem($evento->imagem);
            $dados['imagem'] = null;
        }

        $evento->update($dados);

        return redirect()->route('admin.eventos')->with('sucesso', 'Evento atualizado com sucesso!');
    }

    // US006 - Excluir evento
    public function excluirEvento($id)
    {
        $evento = Evento::findOrFail($id);
        $this->removerImagem($evento->imagem);
        $evento->delete();
        return redirect()->route('admin.eventos')->with('sucesso', 'Evento removido.');
    }

    // US008 - Voluntários inscritos em um evento
    public function voluntariosEvento($id)
    {
        $evento = Evento::with('voluntarios')->findOrFail($id);
        return view('admin.eventos.voluntarios', compact('evento'));
    }

    // Avisos admin
    public function avisos()
    {
        $avisos = Aviso::latest()->get();
        return view('admin.avisos.index', compact('avisos'));
    }

    public function criarAviso()
    {
        return view('admin.avisos.criar');
    }

    public function salvarAviso(Request $request)
    {
        $request->validate([
            'titulo'   => 'required|string|max:255',
            'conteudo' => 'required|string',
        ]);

        Aviso::create([
            'titulo'    => $request->titulo,
            'conteudo'  => $request->conteudo,
            'destaque'  => $request->has('destaque'),
        ]);

        return redirect()->route('admin.avisos')->with('sucesso', 'Aviso publicado com sucesso!');
    }

    // Formulário para editar aviso
    public function editarAviso($id)
    {
        $aviso = Aviso::findOrFail($id);
        return view('admin.avisos.editar', compact('aviso'));
    }

    // Atualizar aviso
    public function atualizarAviso(Request $request, $id)
    {
        $request->validate([
            'titulo'   => 'required|string|max:255',
            'conteudo' => 'required|string',
        ], [
            'titulo.required'   => 'O título é obrigatório.',
            'conteudo.required' => 'O conteúdo é obrigatório.',
        ]);

        $aviso = Aviso::findOrFail($id);
        $aviso->update([
            'titulo'   => $request->titulo,
            'conteudo' => $request->conteudo,
            'destaque' => $request->has('destaque'),
        ]);

        return redirect()->route('admin.avisos')->with('sucesso', 'Aviso atualizado com sucesso!');
    }

    public function excluirAviso($id)
    {
        Aviso::findOrFail($id)->delete();
        return redirect()->route('admin.avisos')->with('sucesso', 'Aviso removido.');
    }

    // ========================================
    // US013 - CRUD de Grupos (Sprint 3)
    // ========================================
    public function grupos()
    {
        $grupos = Grupo::withCount('inscritos')->orderBy('nome')->get();
        return view('admin.grupos.index', compact('grupos'));
    }

    public function criarGrupo()
    {
        return view('admin.grupos.criar');
    }

    public function salvarGrupo(Request $request)
    {
        $request->validate(array_merge([
            'nome'             => 'required|string|max:255',
            'descricao'        => 'required|string',
            'responsavel'      => 'nullable|string|max:255',
            'dia_reuniao'      => 'nullable|string|max:50',
            'horario_reuniao'  => 'nullable|date_format:H:i',
            'local'            => 'nullable|string|max:255',
        ], $this->regrasImagem()), [
            'nome.required'      => 'O nome do grupo é obrigatório.',
            'descricao.required' => 'A descrição é obrigatória.',
        ]);

        Grupo::create([
            'nome'             => $request->nome,
            'descricao'        => $request->descricao,
            'responsavel'      => $request->responsavel,
            'dia_reuniao'      => $request->dia_reuniao,
            'horario_reuniao'  => $request->horario_reuniao,
            'local'            => $request->local,
            'imagem'           => $this->salvarImagem($request, 'grupos'),
            'ativo'            => true,
        ]);

        return redirect()->route('admin.grupos')->with('sucesso', 'Grupo cadastrado com sucesso!');
    }

    public function editarGrupo($id)
    {
        $grupo = Grupo::findOrFail($id);
        return view('admin.grupos.editar', compact('grupo'));
    }

    public function atualizarGrupo(Request $request, $id)
    {
        $request->validate(array_merge([
            'nome'             => 'required|string|max:255',
            'descricao'        => 'required|string',
            'responsavel'      => 'nullable|string|max:255',
            'dia_reuniao'      => 'nullable|string|max:50',
            'horario_reuniao'  => 'nullable|date_format:H:i',
            'local'            => 'nullable|string|max:255',
        ], $this->regrasImagem()));

        $grupo = Grupo::findOrFail($id);
        $dados = $request->only(
            'nome', 'descricao', 'responsavel', 'dia_reuniao', 'horario_reuniao', 'local'
        );

        // Foto nova substitui a antiga; checkbox remover_imagem apaga a atual
        if ($caminho = $this->salvarImagem($request, 'grupos')) {
            $this->removerImagem($grupo->imagem);
            $dados['imagem'] = $caminho;
        } elseif ($request->has('remover_imagem')) {
            $this->removerImagem($grupo->imagem);
            $dados['imagem'] = null;
        }

        $grupo->update($dados);

        return redirect()->route('admin.grupos')->with('sucesso', 'Grupo atualizado com sucesso!');
    }

    public function alternarGrupo($id)
    {
        $grupo = Grupo::findOrFail($id);
        $grupo->ativo = ! $grupo->ativo;
        $grupo->save();

        $msg = $grupo->ativo ? 'Grupo ativado.' : 'Grupo desativado.';
        return redirect()->route('admin.grupos')->with('sucesso', $msg);
    }

    public function excluirGrupo($id)
    {
        $grupo = Grupo::findOrFail($id);
        $this->removerImagem($grupo->imagem);
        $grupo->inscritos()->detach();
        $grupo->delete();
        return redirect()->route('admin.grupos')->with('sucesso', 'Grupo removido.');
    }

    public function inscritosGrupo($id)
    {
        $grupo = Grupo::with('inscritos')->findOrFail($id);
        return view('admin.grupos.inscritos', compact('grupo'));
    }

    // ========================================
    // US014 - CRUD de Missas (Sprint 3)
    // ========================================
    public function missas()
    {
        $missas = Missa::listarOrdenadas();
        return view('admin.missas.index', compact('missas'));
    }

    public function criarMissa()
    {
        return view('admin.missas.criar');
    }

    public function salvarMissa(Request $request)
    {
        $request->validate([
            'dia_semana' => 'required|in:Segunda-feira,Terça-feira,Quarta-feira,Quinta-feira,Sexta-feira,Sábado,Domingo',
            'horario'    => 'required|date_format:H:i',
            'local'      => 'nullable|string|max:255',
            'observacao' => 'nullable|string|max:255',
        ], [
            'dia_semana.required' => 'Escolha o dia da semana.',
            'dia_semana.in'       => 'Dia da semana inválido.',
            'horario.required'    => 'Informe o horário.',
            'horario.date_format' => 'Horário inválido (use HH:MM).',
        ]);

        Missa::create([
            'dia_semana' => $request->dia_semana,
            'horario'    => $request->horario,
            'local'      => $request->local,
            'observacao' => $request->observacao,
            'ativo'      => true,
        ]);

        return redirect()->route('admin.missas')->with('sucesso', 'Horário de missa cadastrado!');
    }

    public function editarMissa($id)
    {
        $missa = Missa::findOrFail($id);
        return view('admin.missas.editar', compact('missa'));
    }

    public function atualizarMissa(Request $request, $id)
    {
        $request->validate([
            'dia_semana' => 'required|in:Segunda-feira,Terça-feira,Quarta-feira,Quinta-feira,Sexta-feira,Sábado,Domingo',
            'horario'    => 'required|date_format:H:i',
            'local'      => 'nullable|string|max:255',
            'observacao' => 'nullable|string|max:255',
        ]);

        $missa = Missa::findOrFail($id);
        $missa->update($request->only('dia_semana', 'horario', 'local', 'observacao'));

        return redirect()->route('admin.missas')->with('sucesso', 'Horário atualizado.');
    }

    public function alternarMissa($id)
    {
        $missa = Missa::findOrFail($id);
        $missa->ativo = ! $missa->ativo;
        $missa->save();

        $msg = $missa->ativo ? 'Missa ativada.' : 'Missa desativada.';
        return redirect()->route('admin.missas')->with('sucesso', $msg);
    }

    public function excluirMissa($id)
    {
        Missa::findOrFail($id)->delete();
        return redirect()->route('admin.missas')->with('sucesso', 'Horário removido.');
    }
}
