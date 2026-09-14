@props([
    'mensagem',
    'rotulo' => 'WhatsApp',
    'classe' => 'btn btn-sm btn-whats',
    'icone'  => true,
])

{{-- Contato por WhatsApp: link wa.me (click-to-chat), sem custo e sem API --}}
<a href="https://wa.me/{{ config('paroquia.whatsapp') }}?text={{ rawurlencode($mensagem) }}"
   target="_blank" rel="noopener" class="{{ $classe }}" title="Falar pelo WhatsApp">
    @if($icone)<i class="bi bi-whatsapp"></i>@endif {{ $rotulo }}
</a>
