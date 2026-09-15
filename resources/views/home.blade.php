@extends('layouts.app')

@section('title', 'Início')

@section('hero')
{{-- Hero com carrossel de imagens (5s) --}}
<section class="hero-igreja">
    <div class="hero-igreja-slides" aria-hidden="true">
        <div class="hero-slide active" style="background-image: url('{{ asset('images/sobre7.jpg') }}');"></div>
        <div class="hero-slide" style="background-image: url('{{ asset('images/sobre8.jpg') }}');"></div>
        <div class="hero-slide" style="background-image: url('{{ asset('images/igreja4k.png') }}');"></div>
        <div class="hero-slide" style="background-image: url('{{ asset('images/sobre4.jpeg') }}');"></div>
    </div>
    <div class="container">
        <h1 class="mb-3">Paróquia Nossa Senhora da Glória</h1>
        <p class="lead mb-1">Igreja Católica Ucraniana · Rito Bizantino · Pitanga/PR</p>
        <p class="lead mb-4">Bem-vindo à nossa comunidade de fé desde 1952</p>
        <div class="hero-acoes">
            <a href="{{ route('missas.index') }}" class="btn btn-hero">
                <i class="bi bi-clock"></i> Horários de missas
            </a>
            <a href="{{ route('sobre') }}" class="btn btn-outline-light">
                <i class="bi bi-info-circle"></i> Conheça a paróquia
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length < 2) return;
    let atual = 0;
    setInterval(function () {
        slides[atual].classList.remove('active');
        atual = (atual + 1) % slides.length;
        slides[atual].classList.add('active');
    }, 5000);
})();
</script>
@endpush

@section('content')

{{-- Celebracoes da semana: proxima celebracao + agenda, no mesmo bloco --}}
@php
    $nomesCurtos = ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB'];
    $hojeIdx     = (int) now()->dayOfWeek;
    $temAgenda   = collect($missasSemana)->contains(fn ($dia) => $dia->isNotEmpty());
@endphp

@if($proximaMissa || $temAgenda)
    <section class="mb-4" aria-labelledby="titulo-celebracoes">
        <h2 class="visually-hidden" id="titulo-celebracoes">Celebrações da semana</h2>

        @if($proximaMissa)
            <div class="proxima-missa faixa-flex mb-3">
                <div class="me-auto">
                    <p class="rotulo mb-1">Próxima celebração</p>
                    <p class="valor">
                        {{ $proximaMissa['quando'] }}, às {{ $proximaMissa['horario'] }}
                        @if($proximaMissa['missa']->observacao)
                            <span class="etiqueta-tipo">{{ $proximaMissa['missa']->observacao }}</span>
                        @endif
                    </p>
                    @if($proximaMissa['missa']->local)
                        <small><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $proximaMissa['missa']->local }}</small>
                    @endif
                </div>
                <a href="{{ route('missas.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-calendar-week" aria-hidden="true"></i> Ver todos os horários
                </a>
            </div>
        @endif

        @if($temAgenda)
            <ul class="agenda-semana" aria-label="Celebrações de domingo a sábado">
                @for($i = 0; $i <= 6; $i++)
                    <li class="agenda-dia {{ $i === $hojeIdx ? 'hoje' : ($i === 0 ? 'domingo' : '') }}">
                        <span class="agenda-nome">{{ $nomesCurtos[$i] }}</span>
                        @forelse($missasSemana[$i] as $missa)
                            <span class="agenda-hora">{{ \Carbon\Carbon::parse($missa->horario)->format('H:i') }}</span>
                        @empty
                            <span class="agenda-hora vazio" aria-label="sem celebração">·</span>
                        @endforelse
                    </li>
                @endfor
            </ul>
        @endif
    </section>
@endif

{{-- Avisos em destaque --}}
@if($avisosDestaque->isNotEmpty())
    <section class="mb-4" aria-labelledby="titulo-avisos">
        <x-secao titulo="Avisos em destaque" icone="bi-megaphone"
                 :link="route('avisos.index')" linkTexto="Ver todos os avisos" id="titulo-avisos" />
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
            @foreach($avisosDestaque as $aviso)
                <div class="col">
                    <article class="panel-card h-100 card-aviso">
                        <div class="panel-body">
                            <h3 class="card-titulo">{{ $aviso->titulo }}</h3>
                            <p class="small text-muted mb-0">{{ \Illuminate\Support\Str::limit($aviso->conteudo, 140) }}</p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
@endif

{{-- Proximos eventos: a secao inteira so aparece quando ha eventos --}}
@if($proximosEventos->isNotEmpty())
    <section class="mb-4" aria-labelledby="titulo-eventos">
        <x-secao titulo="Próximos eventos" icone="bi-calendar-event"
                 :link="route('eventos.index')" linkTexto="Ver todos os eventos" id="titulo-eventos" />
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
            @foreach($proximosEventos as $evento)
                <div class="col"><x-card-evento :evento="$evento" /></div>
            @endforeach
        </div>
    </section>
@endif

{{-- Grupos e pastorais --}}
@if($gruposDestaque->isNotEmpty())
    <section class="mb-4" aria-labelledby="titulo-grupos">
        <x-secao titulo="Nossos grupos e pastorais" icone="bi-people"
                 :link="route('grupos.index')" linkTexto="Ver todos os grupos" id="titulo-grupos" />
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3">
            @foreach($gruposDestaque as $grupo)
                <div class="col"><x-card-grupo :grupo="$grupo" :acoes="false" /></div>
            @endforeach
        </div>
    </section>
@endif

{{-- Casamentos e batizados --}}
<div class="panel-card mb-4" style="border-left:5px solid #f0d080;">
    <div class="panel-body faixa-flex">
        <span class="topbar-icon"><i class="bi bi-heart"></i></span>
        <div class="me-auto">
            <h2 class="card-titulo mb-1">Casamentos e batizados</h2>
            <p class="mb-0 small text-muted">
                O agendamento é feito <strong>pessoalmente na secretaria</strong>, com antecedência.
                Veja os documentos necessários e marque sua conversa.
            </p>
        </div>
        <a href="{{ route('sacramentos') }}" class="btn btn-sm text-white" style="background-color:#1a3a5c;">
            → Ver informações
        </a>
    </div>
</div>

{{-- Onde estamos --}}
<div class="panel-card mb-2">
    <div class="panel-head" style="background-color:#1a3a5c; color:#fff;">
        <i class="bi bi-geo-alt"></i> Onde estamos
    </div>
    <div class="row g-0">
        <div class="col-lg-4">
            <div class="panel-body">
                <strong style="color:#1a3a5c;">Paróquia Nossa Senhora da Glória</strong>
                <p class="text-muted small mb-3">Igreja Católica Ucraniana</p>
                <p class="small mb-2">
                    <i class="bi bi-geo-alt"></i> {{ config('paroquia.endereco.logradouro') }}, {{ config('paroquia.endereco.numero') }}<br>
                    <span class="ms-3">{{ config('paroquia.endereco.bairro') }} · {{ config('paroquia.endereco.cep') }} · {{ config('paroquia.endereco.cidade') }}, {{ config('paroquia.endereco.estado') }}</span>
                </p>
                <p class="small mb-1"><i class="bi bi-telephone"></i> {{ config('paroquia.telefone') }}</p>
                <p class="small mb-3">
                    <a href="mailto:{{ config('paroquia.email') }}" class="text-decoration-none">
                        <i class="bi bi-envelope"></i> {{ config('paroquia.email') }}
                    </a>
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ config('paroquia.mapa.rota') }}"
                       target="_blank" rel="noopener" class="btn btn-sm text-white" style="background-color:#1a3a5c;">
                        <i class="bi bi-map"></i> Como chegar
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <iframe class="mapa-embed" loading="lazy"
                    title="Mapa com a localização da Paróquia Nossa Senhora da Glória em Pitanga, Paraná"
                    src="{{ config('paroquia.mapa.embed') }}"></iframe>
        </div>
    </div>
</div>

@endsection
