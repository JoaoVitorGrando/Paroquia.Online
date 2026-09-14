@extends('layouts.app')

@section('title', 'Eventos e Festas')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-calendar-event"></i></span>
    <div class="me-auto">
        <h2>Eventos e festas</h2>
        <p class="topbar-sub">Participe do que acontece na nossa comunidade</p>
    </div>
</div>

@if($eventos->isEmpty())
    <div class="alert alert-info">
        Nenhum evento cadastrado no momento.
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($eventos as $evento)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    {{-- Foto do evento: só aparece se existir --}}
                    @if($evento->imagem)
                        <img src="{{ asset($evento->imagem) }}" alt="Foto do evento {{ $evento->titulo }}" class="card-foto">
                    @endif
                    <div class="card-body">
                        <span class="badge mb-2" style="background-color:#f0d080; color:#1a3a5c;">
                            {{ \Carbon\Carbon::parse($evento->data)->translatedFormat('d \d\e M') }}
                        </span>
                        <h5 class="card-title" style="color:#1a3a5c;">{{ $evento->titulo }}</h5>
                        <p class="card-text">{{ $evento->descricao }}</p>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="mb-2 small text-muted">
                            <span class="me-3">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}
                            </span>
                            @if($evento->horario)
                                <span class="me-3">
                                    <i class="bi bi-clock"></i>
                                    {{ \Carbon\Carbon::parse($evento->horario)->format('H:i') }}
                                </span>
                            @endif
                            @if($evento->local)
                                <span>
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $evento->local }}
                                </span>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            {{-- Contato por WhatsApp sobre este evento --}}
                            <x-whatsapp-btn
                                :mensagem="'Olá, vim pelo site da paróquia e gostaria de saber mais sobre o evento *' . $evento->titulo . '*, do dia ' . \Carbon\Carbon::parse($evento->data)->format('d/m') . '.'" />
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
