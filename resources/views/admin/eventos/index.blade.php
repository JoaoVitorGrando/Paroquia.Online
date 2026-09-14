@extends('layouts.app')

@section('title', 'Admin Eventos')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-calendar-event"></i></span>
    <div class="me-auto">
        <h2>Gerenciar eventos</h2>
        <p class="topbar-sub">Cadastre, edite e acompanhe os voluntários de cada evento</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-light">
        <i class="bi bi-speedometer2"></i> Painel
    </a>
    <a href="{{ route('admin.eventos.criar') }}" class="btn btn-sm" style="background-color:#f0d080; color:#1a3a5c; font-weight:600;">
        <i class="bi bi-plus-circle"></i> Novo Evento
    </a>
</div>

@if($eventos->isEmpty())
    <div class="alert alert-info">Nenhum evento cadastrado.</div>
@else
    <div class="panel-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color:#1a3a5c; color:#fff;">
                    <tr>
                        <th>Título</th>
                        <th>Data</th>
                        <th>Local</th>
                        <th class="text-center">Voluntários</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eventos as $evento)
                        <tr>
                            <td>
                                @if($evento->imagem)
                                    <i class="bi bi-image text-muted" title="Evento com foto"></i>
                                @endif
                                {{ $evento->titulo }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</td>
                            <td>{{ $evento->local ?? 'Não informado' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.eventos.voluntarios', $evento->id) }}" class="badge bg-secondary text-decoration-none">
                                    {{ $evento->voluntarios_count }} <i class="bi bi-eye"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.eventos.editar', $evento->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{ route('admin.eventos.excluir', $evento->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Confirmar exclusão?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
