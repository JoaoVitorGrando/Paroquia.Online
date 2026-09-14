@extends('layouts.app')

@section('title', 'Admin Avisos')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-megaphone"></i></span>
    <div class="me-auto">
        <h2>Gerenciar avisos</h2>
        <p class="topbar-sub">Publique, edite e remova os comunicados da paróquia</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-light">
        <i class="bi bi-speedometer2"></i> Painel
    </a>
    <a href="{{ route('admin.avisos.criar') }}" class="btn btn-sm" style="background-color:#f0d080; color:#1a3a5c; font-weight:600;">
        <i class="bi bi-plus-circle"></i> Novo Aviso
    </a>
</div>

@if($avisos->isEmpty())
    <div class="alert alert-info">Nenhum aviso cadastrado.</div>
@else
    <div class="panel-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color:#1a3a5c; color:#fff;">
                    <tr>
                        <th>Título</th>
                        <th>Destaque</th>
                        <th>Data</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($avisos as $aviso)
                        <tr>
                            <td>{{ $aviso->titulo }}</td>
                            <td>
                                @if($aviso->destaque)
                                    <span class="badge" style="background-color:#f0d080; color:#1a3a5c;">Sim</span>
                                @else
                                    <span class="text-muted">Não</span>
                                @endif
                            </td>
                            <td>{{ $aviso->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                {{-- Editar aviso (CRUD completo) --}}
                                <a href="{{ route('admin.avisos.editar', $aviso->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{ route('admin.avisos.excluir', $aviso->id) }}" method="POST" class="d-inline"
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
