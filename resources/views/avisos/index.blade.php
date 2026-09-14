@extends('layouts.app')

@section('title', 'Avisos')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-megaphone"></i></span>
    <div class="me-auto">
        <h2>Avisos da paróquia</h2>
        <p class="topbar-sub">Fique por dentro dos comunicados da secretaria</p>
    </div>
</div>

@php
    $emDestaque = $avisos->where('destaque', true);
    $demais     = $avisos->where('destaque', false);
@endphp

@if($avisos->isEmpty())
    <div class="alert alert-info">Nenhum aviso publicado no momento.</div>
@else

    {{-- Avisos em destaque --}}
    @if($emDestaque->isNotEmpty())
        <h5 class="mb-3" style="color:#1a3a5c;">
            <i class="bi bi-star-fill" style="color:#f0d080;"></i> Em destaque
        </h5>
        <div class="row row-cols-1 g-3 mb-4">
            @foreach($emDestaque as $aviso)
                <div class="col">
                    <div class="panel-card" style="border-left:5px solid #f0d080;">
                        <div class="panel-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <h5 class="mb-2" style="color:#1a3a5c;">{{ $aviso->titulo }}</h5>
                                <span class="badge" style="background-color:#f0d080; color:#1a3a5c;">destaque</span>
                            </div>
                            <p class="mb-2">{{ $aviso->conteudo }}</p>
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i> Publicado em {{ $aviso->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Demais avisos --}}
    @if($demais->isNotEmpty())
        <h5 class="mb-3" style="color:#1a3a5c;">
            <i class="bi bi-list-ul"></i> Outros avisos
        </h5>
        <div class="row row-cols-1 g-3">
            @foreach($demais as $aviso)
                <div class="col">
                    <div class="panel-card" style="border-left:5px solid #dfe3ea;">
                        <div class="panel-body">
                            <h5 class="mb-2" style="color:#1a3a5c;">{{ $aviso->titulo }}</h5>
                            <p class="mb-2">{{ $aviso->conteudo }}</p>
                            <small class="text-muted">
                                <i class="bi bi-calendar3"></i> Publicado em {{ $aviso->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endif
@endsection
