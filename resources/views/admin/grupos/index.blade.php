@extends('layouts.app')

@section('title', 'Admin Grupos')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-people"></i></span>
    <div class="me-auto">
        <h2>Gerenciar grupos</h2>
        <p class="topbar-sub">Cadastre, edite e mantenha atualizados os grupos e pastorais</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-light">
        <i class="bi bi-speedometer2"></i> Painel
    </a>
    <a href="{{ route('admin.grupos.criar') }}" class="btn btn-sm" style="background-color:#f0d080; color:#1a3a5c; font-weight:600;">
        <i class="bi bi-plus-circle"></i> Novo Grupo
    </a>
</div>

@if($grupos->isEmpty())
    <x-vazio icone="bi-people" titulo="Nenhum grupo cadastrado">
        <a href="{{ route('admin.grupos.criar') }}" class="btn btn-sm text-white" style="background-color:#1a3a5c;">
            <i class="bi bi-plus-lg" aria-hidden="true"></i> Cadastrar grupo
        </a>
    </x-vazio>
@else
    <div class="panel-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color:#1a3a5c; color:#fff;">
                    <tr>
                        <th>Nome</th>
                        <th>Responsável</th>
                        <th>Dia / Horário</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grupos as $grupo)
                        <tr>
                            <td>
                                <strong>
                                    @if($grupo->imagem)
                                        <i class="bi bi-image text-muted" title="Grupo com foto"></i>
                                    @endif
                                    {{ $grupo->nome }}
                                </strong>
                                <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($grupo->descricao, 80) }}</small>
                            </td>
                            <td>{{ $grupo->responsavel ?? '-' }}</td>
                            <td>
                                {{ $grupo->dia_reuniao ?? '-' }}
                                @if($grupo->horario_reuniao)
                                    às {{ \Carbon\Carbon::parse($grupo->horario_reuniao)->format('H:i') }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($grupo->ativo)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-secondary">Inativo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.grupos.editar', $grupo->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.grupos.alternar', $grupo->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning me-1" title="Ativar/Desativar">
                                        <i class="bi bi-power"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.grupos.excluir', $grupo->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Confirmar exclusão deste grupo?')">
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
