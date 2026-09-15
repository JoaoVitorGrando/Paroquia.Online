@extends('layouts.app')

@section('title', 'Eventos e Festas')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-calendar-event" aria-hidden="true"></i></span>
    <div class="me-auto">
        <h1>Eventos e festas</h1>
        <p class="topbar-sub">Participe do que acontece na nossa comunidade</p>
    </div>
</div>

@php
    $hoje      = now()->startOfDay();
    $proximos  = $eventos->filter(fn ($e) => $e->data >= $hoje)->values();
    $passados  = $eventos->filter(fn ($e) => $e->data < $hoje)->sortByDesc('data')->values();
@endphp

@if($proximos->isNotEmpty())
    <section class="mb-4" aria-labelledby="titulo-proximos">
        <x-secao titulo="Próximos eventos" icone="bi-calendar-check" id="titulo-proximos" />
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach($proximos as $evento)
                <div class="col"><x-card-evento :evento="$evento" :resumo="150" /></div>
            @endforeach
        </div>
    </section>
@endif

@if($passados->isNotEmpty())
    <section class="mb-4" aria-labelledby="titulo-passados">
        <x-secao titulo="Já aconteceram" icone="bi-clock-history" id="titulo-passados" />
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 eventos-passados">
            @foreach($passados as $evento)
                <div class="col"><x-card-evento :evento="$evento" :resumo="110" /></div>
            @endforeach
        </div>
    </section>
@endif

@if($eventos->isEmpty())
    <x-vazio icone="bi-calendar-x" titulo="Nenhum evento marcado no momento"
             texto="Assim que a paróquia agendar a próxima festa ou celebração especial, ela aparece aqui.">
        <x-whatsapp-btn
            mensagem="Olá, vim pelo site da paróquia e gostaria de saber sobre os próximos eventos."
            rotulo="Perguntar pelo WhatsApp" />
    </x-vazio>
@endif
@endsection
