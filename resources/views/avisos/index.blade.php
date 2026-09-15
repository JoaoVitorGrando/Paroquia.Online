@extends('layouts.app')

@section('title', 'Avisos')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-megaphone" aria-hidden="true"></i></span>
    <div class="me-auto">
        <h1>Avisos da paróquia</h1>
        <p class="topbar-sub">Fique por dentro dos comunicados da secretaria</p>
    </div>
</div>

@php
    $emDestaque = $avisos->where('destaque', true);
    $demais     = $avisos->where('destaque', false);
@endphp

@if($emDestaque->isNotEmpty())
    <section class="mb-4" aria-labelledby="titulo-destaque">
        <x-secao titulo="Em destaque" icone="bi-star-fill" id="titulo-destaque" />
        <div class="row row-cols-1 g-3">
            @foreach($emDestaque as $aviso)
                <div class="col">
                    <article class="panel-card card-aviso">
                        <div class="panel-body">
                            <h3 class="card-titulo">{{ $aviso->titulo }}</h3>
                            <p class="mb-2">{{ $aviso->conteudo }}</p>
                            <p class="small text-muted mb-0">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                Publicado em {{ $aviso->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
@endif

@if($demais->isNotEmpty())
    <section class="mb-4" aria-labelledby="titulo-outros">
        <x-secao titulo="Outros avisos" icone="bi-list-ul" id="titulo-outros" />
        <div class="row row-cols-1 g-3">
            @foreach($demais as $aviso)
                <div class="col">
                    <article class="panel-card">
                        <div class="panel-body">
                            <h3 class="card-titulo">{{ $aviso->titulo }}</h3>
                            <p class="mb-2">{{ $aviso->conteudo }}</p>
                            <p class="small text-muted mb-0">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                Publicado em {{ $aviso->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
@endif

@if($avisos->isEmpty())
    <x-vazio icone="bi-megaphone" titulo="Nenhum aviso publicado"
             texto="Quando a secretaria publicar um comunicado, ele aparece aqui." />
@endif
@endsection
