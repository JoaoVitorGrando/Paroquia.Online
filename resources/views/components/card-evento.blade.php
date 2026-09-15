@props(['evento', 'resumo' => 110])

{{-- Cartao de evento usado na home e na listagem de eventos. --}}
<article class="card h-100 shadow-sm">
    <div class="position-relative">
        <img src="{{ asset($evento->imagem ?: 'images/sobre1.jpeg') }}"
             alt="{{ $evento->imagem ? 'Cartaz do evento ' . $evento->titulo : '' }}"
             class="card-foto" loading="lazy" decoding="async">
        <span class="card-data">
            {{ \Carbon\Carbon::parse($evento->data)->format('d') }}
            <small>{{ \Carbon\Carbon::parse($evento->data)->translatedFormat('M') }}</small>
        </span>
    </div>
    <div class="card-body d-flex flex-column">
        <h3 class="card-titulo">{{ $evento->titulo }}</h3>
        <p class="small text-muted">{{ \Illuminate\Support\Str::limit($evento->descricao, $resumo) }}</p>
        <ul class="card-meta small text-muted mb-3">
            <li><i class="bi bi-calendar3" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</li>
            @if($evento->horario)
                <li><i class="bi bi-clock" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($evento->horario)->format('H:i') }}</li>
            @endif
            @if($evento->local)
                <li><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $evento->local }}</li>
            @endif
        </ul>
        <div class="mt-auto">
            <x-whatsapp-btn
                :mensagem="'Olá, vim pelo site da paróquia e gostaria de saber mais sobre o evento *' . $evento->titulo . '*, do dia ' . \Carbon\Carbon::parse($evento->data)->format('d/m') . '.'"
                classe="btn btn-sm btn-whats w-100" />
        </div>
    </div>
</article>
