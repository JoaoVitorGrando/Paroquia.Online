@extends('layouts.app')

@section('title', 'Catequese')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-book"></i></span>
    <div class="me-auto">
        <h1>Catequese</h1>
        <p class="topbar-sub">Formação na fé para crianças, jovens e adultos</p>
    </div>
</div>

{{-- Apresentação + CTA --}}
<div class="panel-card mb-4" style="background-color:#eafaf0; border-color:#bde7cc;">
    <div class="panel-body d-flex flex-wrap align-items-center gap-3">
        <i class="bi bi-book-half" style="font-size:1.8rem; color:#1a3a5c; flex-shrink:0;"></i>
        <div class="me-auto">
            <p class="mb-0">
                A catequese da paróquia realiza-se <strong>somente aos sábados, das 8h30 às 11h30</strong>, ministrada pelas
                <strong>Irmãs Servas de Maria Imaculada</strong>, pelas <strong>Catequistas do Sagrado Coração</strong>
                e por catequistas leigas da comunidade. As inscrições acontecem no início de cada ano, na secretaria
                paroquial ou pelo WhatsApp.
            </p>
        </div>
        <x-whatsapp-btn
            mensagem="Olá, vim pelo site da paróquia e gostaria de informações sobre a inscrição na catequese."
            rotulo="Inscrever pelo WhatsApp"
            classe="btn btn-whats" />
    </div>
</div>

{{-- Horário único de todas as turmas --}}
<div class="panel-card mb-4">
    <div class="panel-body d-flex flex-wrap align-items-center gap-3">
        <span class="topbar-icon"><i class="bi bi-calendar-week"></i></span>
        <div>
            <strong style="color:#1a3a5c;">Sábados, das 8h30 às 11h30</strong>
            <p class="small text-muted mb-0">Todas as turmas se encontram no mesmo horário, no salão paroquial.</p>
        </div>
    </div>
</div>

{{-- Turmas oferecidas --}}
<h2 class="secao-titulo mb-3"><i class="bi bi-mortarboard" aria-hidden="true"></i> Turmas oferecidas</h2>

@php
    $turmas = [
        ['nome' => 'Iniciação à fé',        'idade' => '7 a 9 anos',
         'texto' => 'Primeiro contato com a fé, as orações e a história da salvação.'],
        ['nome' => 'Primeira Eucaristia',   'idade' => '10 a 12 anos',
         'texto' => 'Preparação para receber o sacramento da Primeira Comunhão.'],
        ['nome' => 'Crisma',                'idade' => '13 anos ou mais',
         'texto' => 'Preparação para o sacramento da Confirmação (Crisma).'],
        ['nome' => 'Catequese de adultos',  'idade' => 'a partir de 18 anos',
         'texto' => 'Para adultos que ainda não receberam os sacramentos ou desejam aprofundar a fé.'],
    ];
@endphp

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 mb-4">
    @foreach($turmas as $turma)
        <div class="col">
            <div class="panel-card h-100">
                <div class="panel-body d-flex flex-column h-100">
                    <h3 class="card-titulo">{{ $turma['nome'] }}</h3>
                    <span class="badge mb-2 align-self-start" style="background-color:#eaf0fb; color:#1a3a5c;">
                        {{ $turma['idade'] }}
                    </span>
                    <p class="small text-muted mb-3">{{ $turma['texto'] }}</p>
                    <div class="mt-auto">
                        <x-whatsapp-btn
                            :mensagem="'Olá, vim pelo site da paróquia e gostaria de informações sobre a inscrição na catequese, turma *' . $turma['nome'] . '*.'"
                            rotulo="Quero informações" />
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    {{-- Documentos --}}
    <div class="col-lg-6">
        <div class="panel-card h-100">
            <div class="panel-head"><i class="bi bi-clipboard-check"></i> Documentos para inscrição</div>
            <div class="panel-body">
                <ul class="mb-0">
                    <li>Certidão de batismo do catequizando</li>
                    <li>Documento com foto do responsável</li>
                    <li>Comprovante de endereço</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Como se inscrever --}}
    <div class="col-lg-6">
        <div class="panel-card h-100">
            <div class="panel-head"><i class="bi bi-info-circle"></i> Como se inscrever</div>
            <div class="panel-body">
                <p>
                    As inscrições são feitas na secretaria paroquial, de <strong>segunda a sexta,
                    das 09h às 12h e das 14h às 17h</strong>, ou pelo WhatsApp. Dúvidas sobre turmas,
                    idades ou documentos podem ser tiradas diretamente com a secretaria.
                </p>
                <x-whatsapp-btn
                    mensagem="Olá, vim pelo site da paróquia e gostaria de informações sobre a inscrição na catequese."
                    rotulo="Falar com a secretaria" />
            </div>
        </div>
    </div>
</div>

<p class="text-muted small mt-3 mb-0">
    <i class="bi bi-info-circle"></i>
    As idades e os documentos são uma referência e devem ser confirmados com a secretaria paroquial.
</p>
@endsection
