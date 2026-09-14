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
        {{-- bi-church não existe no Bootstrap Icons; bi-house-heart existe e combina com o tema --}}
        <h1 class="mb-3"><i class="bi bi-house-heart"></i> Paróquia Nossa Senhora da Glória</h1>
        <p class="lead mb-1">Igreja Católica Ucraniana · Rito Bizantino · Pitanga/PR</p>
        <p class="lead mb-4">Bem-vindo à nossa comunidade de fé desde 1952</p>
        <a href="{{ route('missas.index') }}" class="btn btn-hero me-2">
            <i class="bi bi-clock"></i> Horários de missas
        </a>
        <a href="{{ route('sobre') }}" class="btn btn-outline-light">
            <i class="bi bi-info-circle"></i> Conheça a paróquia
        </a>
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

{{-- Faixa "Próxima missa" --}}
@if($proximaMissa)
    <div class="proxima-missa d-flex flex-wrap align-items-center gap-3 mb-3">
        <div class="me-auto">
            <div class="rotulo">Próxima missa</div>
            <p class="valor">{{ $proximaMissa['quando'] }}, às {{ $proximaMissa['horario'] }}</p>
            @if($proximaMissa['missa']->local)
                <small><i class="bi bi-geo-alt"></i> {{ $proximaMissa['missa']->local }}</small>
            @endif
        </div>
        <a href="{{ route('missas.index') }}" class="btn btn-sm btn-outline-light">
            <i class="bi bi-calendar-week"></i> Ver todos os horários
        </a>
    </div>
@endif

{{-- Agenda da semana --}}
@php
    $nomesCurtos = ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB'];
    $hojeIdx = (int) now()->dayOfWeek;
@endphp
<div class="agenda-semana mb-4">
    @for($i = 0; $i <= 6; $i++)
        <div class="agenda-dia {{ $i === $hojeIdx ? 'hoje' : ($i === 0 ? 'domingo' : '') }}">
            <div class="agenda-nome">{{ $nomesCurtos[$i] }}</div>
            @forelse($missasSemana[$i] as $missa)
                <div class="agenda-hora">{{ \Carbon\Carbon::parse($missa->horario)->format('H:i') }}</div>
            @empty
                <div class="agenda-hora text-muted">—</div>
            @endforelse
        </div>
    @endfor
</div>

{{-- Avisos em destaque --}}
@if($avisosDestaque->isNotEmpty())
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0" style="color:#1a3a5c;"><i class="bi bi-megaphone"></i> Avisos em destaque</h5>
        <a href="{{ route('avisos.index') }}" class="small text-decoration-none">Ver todos os avisos →</a>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
        @foreach($avisosDestaque as $aviso)
            <div class="col">
                <div class="panel-card h-100" style="border-left:5px solid #f0d080;">
                    <div class="panel-body">
                        <span class="badge mb-2" style="background-color:#f0d080; color:#1a3a5c;">destaque</span>
                        <h6 style="color:#1a3a5c;">{{ $aviso->titulo }}</h6>
                        <p class="small text-muted mb-0">{{ \Illuminate\Support\Str::limit($aviso->conteudo, 140) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Próximos eventos --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="color:#1a3a5c;"><i class="bi bi-calendar-event"></i> Próximos eventos</h5>
    <a href="{{ route('eventos.index') }}" class="small text-decoration-none">Ver todos os eventos →</a>
</div>
<div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
    @forelse($proximosEventos as $evento)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="position-relative">
                    {{-- Sem foto própria, usa uma imagem neutra da igreja --}}
                    <img src="{{ asset($evento->imagem ?: 'images/sobre1.jpeg') }}"
                         alt="Foto do evento {{ $evento->titulo }}" class="card-foto">
                    <span class="position-absolute top-0 start-0 m-2 px-2 py-1 rounded text-center"
                          style="background-color:#fff; color:#1a3a5c; font-weight:700; line-height:1.1;">
                        {{ \Carbon\Carbon::parse($evento->data)->format('d') }}<br>
                        <small style="font-weight:500;">{{ \Carbon\Carbon::parse($evento->data)->translatedFormat('M') }}</small>
                    </span>
                </div>
                <div class="card-body">
                    <h6 style="color:#1a3a5c;">{{ $evento->titulo }}</h6>
                    <p class="small text-muted mb-2">{{ \Illuminate\Support\Str::limit($evento->descricao, 110) }}</p>
                    <div class="small text-muted">
                        @if($evento->horario)
                            <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($evento->horario)->format('H:i') }}
                        @endif
                        @if($evento->local)
                            <span class="ms-2"><i class="bi bi-geo-alt"></i> {{ $evento->local }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-info mb-0">Nenhum evento próximo.</div></div>
    @endforelse
</div>

{{-- Grupos e pastorais --}}
@if($gruposDestaque->isNotEmpty())
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0" style="color:#1a3a5c;"><i class="bi bi-people"></i> Nossos grupos e pastorais</h5>
        <a href="{{ route('grupos.index') }}" class="small text-decoration-none">Ver todos os grupos →</a>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
        @foreach($gruposDestaque as $grupo)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset($grupo->imagem ?: 'images/sobre1.jpeg') }}"
                         alt="Foto do grupo {{ $grupo->nome }}"
                         style="height:160px; object-fit:{{ $grupo->imagem ? 'contain' : 'cover' }}; padding:{{ $grupo->imagem ? '12px' : '0' }}; background:#f8f9fa;">
                    <div class="card-body">
                        <h6 style="color:#1a3a5c;">{{ $grupo->nome }}</h6>
                        <p class="small text-muted mb-2">{{ \Illuminate\Support\Str::limit($grupo->descricao, 110) }}</p>
                        @if($grupo->dia_reuniao && $grupo->horario_reuniao)
                            <div class="small text-muted">
                                <i class="bi bi-clock"></i> {{ $grupo->dia_reuniao }}
                                às {{ \Carbon\Carbon::parse($grupo->horario_reuniao)->format('H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Casamentos e batizados --}}
<div class="panel-card mb-4" style="border-left:5px solid #f0d080;">
    <div class="panel-body d-flex flex-wrap align-items-center gap-3">
        <span class="topbar-icon"><i class="bi bi-heart"></i></span>
        <div class="me-auto">
            <h6 class="mb-1" style="color:#1a3a5c;">Casamentos e batizados</h6>
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
                    <i class="bi bi-geo-alt"></i> Rua Conselheiro Zacarias, 295<br>
                    <span class="ms-3">85200-053, Pitanga, Paraná</span>
                </p>
                <p class="small mb-3"><i class="bi bi-telephone"></i> {{ config('paroquia.telefone') }}</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="https://www.google.com/maps/dir/?api=1&destination=Par%C3%B3quia+Nossa+Senhora+da+Gl%C3%B3ria%2C+Pitanga+-+PR"
                       target="_blank" rel="noopener" class="btn btn-sm text-white" style="background-color:#1a3a5c;">
                        <i class="bi bi-map"></i> Como chegar
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <iframe class="mapa-embed" loading="lazy"
                    title="Mapa com a localização da Paróquia Nossa Senhora da Glória em Pitanga, Paraná"
                    src="https://www.google.com/maps?q=Par%C3%B3quia+Nossa+Senhora+da+Gl%C3%B3ria%2C+Pitanga+-+PR&output=embed"></iframe>
        </div>
    </div>
</div>

@endsection
