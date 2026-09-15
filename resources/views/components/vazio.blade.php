@props([
    'icone'    => 'bi-inbox',
    'titulo'   => 'Nada por aqui ainda',
    'texto'    => null,
])

{{-- Estado vazio discreto: sem caixa colorida de alerta, so uma mensagem
     centralizada para a pagina nao parecer quebrada. --}}
<div class="estado-vazio text-center">
    <i class="bi {{ $icone }}" aria-hidden="true"></i>
    <p class="estado-vazio-titulo mb-1">{{ $titulo }}</p>
    @if($texto)
        <p class="small text-muted mb-3">{{ $texto }}</p>
    @endif
    {{ $slot }}
</div>
