@props([
    'titulo',
    'icone'     => null,
    'link'      => null,
    'linkTexto' => 'Ver todos',
    'id'        => null,
])

{{-- Cabecalho padrao de secao: titulo a esquerda, atalho a direita.
     Centraliza o markup que antes se repetia na home e nas listagens. --}}
<div class="section-head mb-3">
    <h2 class="secao-titulo mb-0" @if($id) id="{{ $id }}" @endif>
        @if($icone)<i class="bi {{ $icone }}" aria-hidden="true"></i>@endif
        {{ $titulo }}
    </h2>
    @if($link)
        <a href="{{ $link }}" class="secao-link">{{ $linkTexto }}</a>
    @endif
</div>
