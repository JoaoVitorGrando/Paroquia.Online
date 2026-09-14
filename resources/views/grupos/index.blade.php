@extends('layouts.app')

@section('title', 'Grupos da Paróquia')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-people"></i></span>
    <div class="me-auto">
        <h2>Grupos e pastorais</h2>
        <p class="topbar-sub">Conheça e participe das atividades da nossa comunidade</p>
    </div>
</div>

@if($grupos->isEmpty())
    <div class="alert alert-info">Nenhum grupo cadastrado no momento.</div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($grupos as $grupo)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    {{-- Logo do grupo: só aparece se existir (contain para não cortar a logo) --}}
                    @if($grupo->imagem)
                        <img src="{{ asset($grupo->imagem) }}" alt="Logo do grupo {{ $grupo->nome }}"
                             style="height:190px; object-fit:contain; padding:16px; background:#f8f9fa;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title" style="color:#1a3a5c;">
                            <i class="bi bi-people-fill"></i> {{ $grupo->nome }}
                        </h5>
                        <p class="card-text">{{ $grupo->descricao }}</p>

                        @if($grupo->responsavel)
                            <p class="mb-1 small text-muted">
                                <i class="bi bi-person"></i> Responsável: {{ $grupo->responsavel }}
                            </p>
                        @endif
                        @if($grupo->dia_reuniao && $grupo->horario_reuniao)
                            <p class="mb-1 small text-muted">
                                <i class="bi bi-calendar3"></i> {{ $grupo->dia_reuniao }}
                                às {{ \Carbon\Carbon::parse($grupo->horario_reuniao)->format('H:i') }}
                            </p>
                        @endif
                        @if($grupo->local)
                            <p class="mb-2 small text-muted">
                                <i class="bi bi-geo-alt"></i> {{ $grupo->local }}
                            </p>
                        @endif
                    </div>
                    <div class="card-footer bg-light d-flex flex-wrap gap-2">
                        {{-- Grupo de Dança tem página informativa própria --}}
                        @if(\Illuminate\Support\Str::contains($grupo->nome, 'Dança'))
                            <a href="{{ route('grupos.danca') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-music-note-beamed"></i> Conheça o grupo
                            </a>
                        @endif

                        {{-- Contato por WhatsApp sobre este grupo --}}
                        <x-whatsapp-btn
                            :mensagem="'Olá, vim pelo site da paróquia e gostaria de participar do grupo *' . $grupo->nome . '*.'" />
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
