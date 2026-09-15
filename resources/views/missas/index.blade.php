@extends('layouts.app')

@section('title', 'Horários de Missas')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-clock-history"></i></span>
    <div class="me-auto">
        <h1>Horários de missas</h1>
        <p class="topbar-sub">Missas e novenas da semana na nossa paróquia</p>
    </div>
</div>

{{-- Faixa "Próxima missa" --}}
@if($proximaMissa)
    <div class="proxima-missa faixa-flex mb-4">
        <div class="me-auto">
            <div class="rotulo">Próxima celebração</div>
            <p class="valor">
                {{ $proximaMissa['quando'] }}, às {{ $proximaMissa['horario'] }}
                @if($proximaMissa['missa']->observacao)
                    <span class="badge align-middle" style="background-color:#f0d080; color:#1a3a5c; font-size:.55em; vertical-align:middle;">
                        {{ $proximaMissa['missa']->observacao }}
                    </span>
                @endif
            </p>
            @if($proximaMissa['missa']->local)
                <small><i class="bi bi-geo-alt"></i> {{ $proximaMissa['missa']->local }}</small>
            @endif
        </div>
        <a href="https://www.google.com/maps/dir/?api=1&destination=Par%C3%B3quia+Nossa+Senhora+da+Gl%C3%B3ria%2C+Pitanga+-+PR"
           target="_blank" rel="noopener" class="btn btn-sm btn-outline-light">
            <i class="bi bi-map"></i> Como chegar
        </a>
    </div>
@endif

@if($missas->isEmpty())
    <x-vazio icone="bi-clock" titulo="Nenhum horário publicado"
             texto="Fale com a secretaria para confirmar os horários das celebrações.">
        <x-whatsapp-btn
            mensagem="Olá, vim pelo site da paróquia e gostaria de saber os horários das missas."
            rotulo="Perguntar pelo WhatsApp" />
    </x-vazio>
@else
    @php
        $hojeIdx = (int) now()->dayOfWeek;
        $diaAnterior = null;
    @endphp

    <h2 class="visually-hidden">Tabela de horários</h2>
    <div class="panel-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Dia da semana</th>
                        <th>Horário</th>
                        <th>Local</th>
                        <th>Observação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($missas as $missa)
                        @php
                            $indice     = \App\Models\Missa::indiceDia($missa->dia_semana);
                            $ehHoje     = ($indice !== null && $indice === $hojeIdx);
                            $ehDomingo  = ($indice === 0);
                            $primeiraDoDia = ($indice !== $diaAnterior);
                            $diaAnterior = $indice;

                            $fundo = $ehHoje ? '#eaf0fb' : ($ehDomingo ? '#fdf6e3' : 'transparent');
                        @endphp
                        <tr style="background-color: {{ $fundo }};">
                            <td>
                                {{-- O dia aparece em toda linha: quando ha mais de um horario
                                     no mesmo dia, a repeticao fica discreta em vez de deixar
                                     a celula vazia. --}}
                                @if($primeiraDoDia)
                                    <strong style="color:#1a3a5c;">{{ $missa->dia_semana }}</strong>
                                    @if($ehHoje)
                                        <span class="badge ms-1" style="background-color:#1a3a5c;">hoje</span>
                                    @elseif($ehDomingo)
                                        <span class="badge ms-1" style="background-color:#f0d080; color:#1a3a5c;">principal</span>
                                    @endif
                                @else
                                    <span class="text-muted">{{ $missa->dia_semana }}</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($missa->horario)->format('H:i') }}</td>
                            <td>{{ $missa->local ?? '-' }}</td>
                            <td class="text-muted">{{ $missa->observacao ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p class="text-muted small mt-3 mb-0">
        <i class="bi bi-info-circle"></i>
        Os horários podem sofrer alterações em datas especiais e festas da paróquia.
    </p>
@endif
@endsection
