@extends('layouts.app')

@section('title', 'Inscritos no grupo')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-people-fill"></i></span>
    <div class="me-auto">
        <h2>Inscritos — {{ $grupo->nome }}</h2>
        <p class="topbar-sub">{{ $grupo->inscritos->count() }} pessoa(s) inscrita(s) neste grupo</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-sm btn-outline-light">
        <i class="bi bi-speedometer2"></i> Painel
    </a>
    <a href="{{ route('admin.grupos') }}" class="btn btn-sm btn-outline-light">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

@if($grupo->inscritos->isEmpty())
    <div class="alert alert-info">Ainda não há inscritos neste grupo.</div>
@else
    <div class="panel-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color:#1a3a5c; color:#fff;">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Inscrito em</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grupo->inscritos as $i => $u)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ \Carbon\Carbon::parse($u->pivot->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
