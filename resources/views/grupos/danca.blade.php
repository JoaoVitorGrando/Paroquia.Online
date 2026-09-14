@extends('layouts.app')

@section('title', 'Grupo Folclórico Ucraniano Kyiv')


@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-music-note-beamed"></i></span>
    <div class="me-auto">
        <h2>Grupo Folclórico Ucraniano Kyiv</h2>
        <p class="topbar-sub">Preservando a cultura ucraniana em Pitanga desde 1972</p>
    </div>
    <a href="{{ route('grupos.index') }}" class="btn btn-sm btn-outline-light">
        <i class="bi bi-arrow-left"></i> Voltar aos grupos
    </a>
</div>

{{-- Banner de destaque: galeria de fotos em carrossel --}}
@if($fotos->isNotEmpty())
<div id="carrosselDanca" class="carousel slide carousel-fade mb-4 rounded overflow-hidden shadow-sm"
     data-bs-ride="carousel" data-bs-interval="4000" style="background:#1a3a5c;">
    <div class="carousel-inner">
        @foreach($fotos as $i => $foto)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                <img src="{{ asset($foto) }}" class="d-block w-100"
                     style="max-height:400px; object-fit:cover;" alt="Foto {{ $i+1 }} do grupo">
            </div>
        @endforeach
    </div>
    @if($fotos->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#carrosselDanca" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carrosselDanca" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    @endif
</div>
@endif

<div class="row g-4">
    {{-- História --}}
    <div class="col-lg-8">
        <div class="panel-card h-100">
            <div class="panel-head"><i class="bi bi-book"></i> Nossa história</div>
            <div class="panel-body">
                <p>
                    Fundado em <strong>1972</strong>, o Grupo Folclórico Ucraniano Kyiv é uma
                    associação cultural sem fins lucrativos ligada à Paróquia Nossa Senhora da
                    Glória, da Igreja Greco-Católica Ucraniana. O nome <em>Kyiv</em> é uma
                    homenagem à capital da Ucrânia, importante centro cultural, religioso e
                    histórico do país.
                </p>
                <p>
                    Hoje o grupo reúne cerca de <strong>110 integrantes</strong>, organizados nas
                    categorias pré-infantil, infantil, juvenil/adulto e veteranos — mantendo viva
                    a tradição de geração em geração. Trajando os coloridos vestuários típicos e
                    portando seus estandartes, o Kyiv preserva e compartilha a rica herança do
                    folclore ucraniano por meio da dança.
                </p>
                <p class="mb-0">
                    Ao longo de sua história, o grupo já se apresentou em palcos como o
                    <strong>Teatro Guaíra</strong>, em Curitiba, e sediou em 2022 a 28ª edição do
                    Festival Nacional de Danças Ucranianas. Vinculado à igreja, mantém o
                    compromisso de valorizar a fé e a cultura da comunidade. Quer fazer parte?
                    Todos são bem-vindos!
                </p>
            </div>
        </div>
    </div>

    {{-- Informações do grupo --}}
    <div class="col-lg-4">
        <div class="panel-card h-100">
            <div class="panel-head"><i class="bi bi-info-circle"></i> Informações</div>
            <div class="panel-body">
                @if($grupo && $grupo->dia_reuniao && $grupo->horario_reuniao)
                    <p class="mb-2">
                        <i class="bi bi-calendar3 text-primary"></i>
                        <strong>Ensaios:</strong> {{ $grupo->dia_reuniao }}
                        às {{ \Carbon\Carbon::parse($grupo->horario_reuniao)->format('H:i') }}
                    </p>
                @endif
                @if($grupo && $grupo->local)
                    <p class="mb-2">
                        <i class="bi bi-geo-alt text-primary"></i>
                        <strong>Local:</strong> {{ $grupo->local }}
                    </p>
                @endif
                @if($grupo && $grupo->responsavel)
                    <p class="mb-3">
                        <i class="bi bi-person text-primary"></i>
                        <strong>Responsável:</strong> {{ $grupo->responsavel }}
                    </p>
                @endif

                <x-whatsapp-btn
                    mensagem="Olá, vim pelo site da paróquia e gostaria de participar do Grupo Folclórico Ucraniano Kyiv."
                    rotulo="Falar pelo WhatsApp"
                    classe="btn btn-whats w-100 mb-2" />
                <a href="https://www.instagram.com/folclorekyivpitanga/" target="_blank" rel="noopener"
                   class="btn btn-outline-primary w-100">
                    <i class="bi bi-instagram"></i> @folclorekyivpitanga
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Galeria de fotos --}}
@if($fotos->count() > 1)
    <div class="panel-card mt-4">
        <div class="panel-head"><i class="bi bi-images"></i> Galeria de fotos</div>
        <div class="panel-body">
            <div class="row g-3">
                @foreach($fotos as $foto)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ asset($foto) }}" target="_blank" rel="noopener">
                            <img src="{{ asset($foto) }}" alt="Apresentação do grupo de dança"
                                 class="w-100" style="height:180px; object-fit:cover; border-radius:10px;">
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection
