@props(['grupo', 'resumo' => 110, 'acoes' => true])

{{-- Cartao de grupo usado na home e na vitrine de grupos. --}}
<article class="card h-100 shadow-sm">
    @if($grupo->imagem)
        <img src="{{ asset($grupo->imagem) }}" alt="Logo do grupo {{ $grupo->nome }}"
             class="card-logo" loading="lazy" decoding="async">
    @endif
    <div class="card-body d-flex flex-column">
        <h3 class="card-titulo">{{ $grupo->nome }}</h3>
        <p class="small text-muted">{{ \Illuminate\Support\Str::limit($grupo->descricao, $resumo) }}</p>
        <ul class="card-meta small text-muted mb-3">
            @if($grupo->responsavel)
                <li><i class="bi bi-person" aria-hidden="true"></i> {{ $grupo->responsavel }}</li>
            @endif
            @if($grupo->dia_reuniao)
                <li>
                    <i class="bi bi-calendar3" aria-hidden="true"></i> {{ $grupo->dia_reuniao }}
                    @if($grupo->horario_reuniao)
                        às {{ \Carbon\Carbon::parse($grupo->horario_reuniao)->format('H:i') }}
                    @endif
                </li>
            @endif
            @if($grupo->local)
                <li><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $grupo->local }}</li>
            @endif
        </ul>
        @if($acoes)
            <div class="mt-auto d-grid gap-2">
                @if(\Illuminate\Support\Str::contains($grupo->nome, 'Dança'))
                    <a href="{{ route('grupos.danca') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-music-note-beamed" aria-hidden="true"></i> Conheça o grupo
                    </a>
                @endif
                <x-whatsapp-btn
                    :mensagem="'Olá, vim pelo site da paróquia e gostaria de participar do grupo *' . $grupo->nome . '*.'"
                    classe="btn btn-sm btn-whats" />
            </div>
        @endif
    </div>
</article>
