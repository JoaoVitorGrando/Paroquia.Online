@extends('layouts.app')

@section('title', 'Painel Admin')

@section('content')

{{-- Barra de identificação do painel --}}
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-speedometer2"></i></span>
    <div class="me-auto">
        <h2>Painel administrativo</h2>
        <p class="topbar-sub">Bem-vindo, {{ Auth::user()->name }}</p>
    </div>
    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light">
        <i class="bi bi-house"></i> Ver o site
    </a>
</div>

{{-- Cartões de indicadores --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-num">{{ $totalEventos }}</div>
                    <div class="stat-label">Eventos</div>
                </div>
                <i class="bi bi-calendar-event fs-4" style="color:#f0d080;"></i>
            </div>
            <div class="stat-extra mt-2"><i class="bi bi-arrow-right-short"></i>{{ $eventosProximos }} próximos</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-num">{{ $totalAvisos }}</div>
                    <div class="stat-label">Avisos</div>
                </div>
                <i class="bi bi-megaphone fs-4" style="color:#f0d080;"></i>
            </div>
            <div class="stat-extra mt-2"><i class="bi bi-arrow-right-short"></i>{{ $avisosDestaque }} em destaque</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-num">{{ $totalMissas }}</div>
                    <div class="stat-label">Missas</div>
                </div>
                <i class="bi bi-clock fs-4" style="color:#f0d080;"></i>
            </div>
            <div class="stat-extra mt-2"><i class="bi bi-arrow-right-short"></i>{{ $missasAtivas }} ativas</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-num">{{ $totalGrupos }}</div>
                    <div class="stat-label">Grupos</div>
                </div>
                <i class="bi bi-people fs-4" style="color:#f0d080;"></i>
            </div>
            <div class="stat-extra mt-2"><i class="bi bi-arrow-right-short"></i>{{ $totalInscritos }} inscritos</div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Próximos eventos --}}
    <div class="col-lg-6">
        <div class="panel-card h-100">
            <div class="panel-head d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event"></i> Próximos eventos</span>
                <a href="{{ route('admin.eventos') }}" class="small text-decoration-none">Gerenciar</a>
            </div>
            <div class="panel-body">
                @forelse($proximosEventos as $evento)
                    <div class="d-flex align-items-start gap-3 {{ $loop->last ? '' : 'mb-3' }}">
                        <span class="badge" style="background-color:#eaf0fb; color:#1a3a5c;">
                            {{ \Carbon\Carbon::parse($evento->data)->format('d/m') }}
                        </span>
                        <div>
                            <strong>{{ $evento->titulo }}</strong>
                            <div class="small text-muted">{{ $evento->local ?? 'Local a confirmar' }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Nenhum evento próximo.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Grupos com mais inscritos --}}
    <div class="col-lg-6">
        <div class="panel-card h-100">
            <div class="panel-head d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart"></i> Grupos com mais inscritos</span>
                <a href="{{ route('admin.grupos') }}" class="small text-decoration-none">Gerenciar</a>
            </div>
            <div class="panel-body">
                @php $maior = $gruposPopulares->max('inscritos_count') ?: 1; @endphp
                @forelse($gruposPopulares as $grupo)
                    <div class="{{ $loop->last ? '' : 'mb-3' }}">
                        <div class="d-flex justify-content-between small">
                            <span>{{ $grupo->nome }}</span>
                            <strong style="color:#1a3a5c;">{{ $grupo->inscritos_count }}</strong>
                        </div>
                        <div class="progress mt-1" style="height:8px;">
                            <div class="progress-bar" role="progressbar"
                                 style="background-color:#1a3a5c; width: {{ round(($grupo->inscritos_count / $maior) * 100) }}%;"
                                 aria-valuenow="{{ $grupo->inscritos_count }}" aria-valuemin="0" aria-valuemax="{{ $maior }}"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Nenhum grupo cadastrado.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Avisos recentes --}}
    <div class="col-lg-6">
        <div class="panel-card h-100">
            <div class="panel-head d-flex justify-content-between align-items-center">
                <span><i class="bi bi-megaphone"></i> Avisos recentes</span>
                <a href="{{ route('admin.avisos') }}" class="small text-decoration-none">Gerenciar</a>
            </div>
            <div class="panel-body">
                @forelse($avisosRecentes as $aviso)
                    <div class="{{ $loop->last ? '' : 'mb-3' }}">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <strong>{{ $aviso->titulo }}</strong>
                            @if($aviso->destaque)
                                <span class="badge" style="background-color:#f0d080; color:#1a3a5c;">destaque</span>
                            @endif
                        </div>
                        <div class="small text-muted">{{ $aviso->created_at->format('d/m/Y') }}</div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Nenhum aviso publicado.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Ações rápidas --}}
    <div class="col-lg-6">
        <div class="panel-card h-100">
            <div class="panel-head"><i class="bi bi-lightning"></i> Ações rápidas</div>
            <div class="panel-body">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('admin.eventos.criar') }}" class="btn w-100 text-white" style="background-color:#1a3a5c;">
                            <i class="bi bi-plus-circle"></i> Novo evento
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.avisos.criar') }}" class="btn w-100 text-white" style="background-color:#1a3a5c;">
                            <i class="bi bi-plus-circle"></i> Novo aviso
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.missas.criar') }}" class="btn w-100 text-white" style="background-color:#1a3a5c;">
                            <i class="bi bi-plus-circle"></i> Novo horário
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.grupos.criar') }}" class="btn w-100 text-white" style="background-color:#1a3a5c;">
                            <i class="bi bi-plus-circle"></i> Novo grupo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
