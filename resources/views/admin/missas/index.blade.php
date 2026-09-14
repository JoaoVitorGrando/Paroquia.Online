@extends('layouts.app')

@section('title', 'Admin Missas')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-clock"></i></span>
    <div class="me-auto">
        <h2>Gerenciar horários de missas</h2>
        <p class="topbar-sub">Cadastre, edite e ative ou desative as celebrações da semana</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-light">
        <i class="bi bi-speedometer2"></i> Painel
    </a>
    <a href="{{ route('admin.missas.criar') }}" class="btn btn-sm" style="background-color:#f0d080; color:#1a3a5c; font-weight:600;">
        <i class="bi bi-plus-circle"></i> Novo Horário
    </a>
</div>

@if($missas->isEmpty())
    <div class="alert alert-info">Nenhum horário cadastrado.</div>
@else
    <div class="panel-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color:#1a3a5c; color:#fff;">
                    <tr>
                        <th>Dia da semana</th>
                        <th>Horário</th>
                        <th>Local</th>
                        <th>Observação</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($missas as $missa)
                        <tr>
                            <td><strong>{{ $missa->dia_semana }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($missa->horario)->format('H:i') }}</td>
                            <td>{{ $missa->local ?? '—' }}</td>
                            <td>{{ $missa->observacao ?? '—' }}</td>
                            <td class="text-center">
                                @if($missa->ativo)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-secondary">Inativo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.missas.editar', $missa->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.missas.alternar', $missa->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning me-1" title="Ativar/Desativar">
                                        <i class="bi bi-power"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.missas.excluir', $missa->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Confirmar exclusão deste horário?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
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
