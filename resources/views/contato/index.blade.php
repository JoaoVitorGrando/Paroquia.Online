@extends('layouts.app')

@section('title', 'Contato')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-envelope"></i></span>
    <div class="me-auto">
        <h1>Entre em contato</h1>
        <p class="topbar-sub">Fale direto com a secretaria paroquial</p>
    </div>
</div>

{{-- WhatsApp em destaque: canal principal pedido pela paróquia --}}
<div class="panel-card mb-4" style="background-color:#eafaf0; border-color:#bde7cc;">
    <div class="panel-body d-flex flex-wrap align-items-center gap-3">
        <i class="bi bi-whatsapp" style="font-size:2.6rem; color:#25d366;"></i>
        <div class="me-auto">
            <h2 class="h6 mb-1" style="color:#0a3d1f;">Fale com a secretaria pelo WhatsApp</h2>
            <p class="mb-0 small text-muted">
                É o jeito mais rápido de tirar dúvidas sobre missas, catequese, batizados e casamentos.
                Você também pode ligar, escrever para o nosso e-mail ou passar na secretaria.
            </p>
        </div>
        <x-whatsapp-btn
            mensagem="Olá, vim pelo site da paróquia e gostaria de falar com a secretaria."
            rotulo="Abrir conversa"
            classe="btn btn-lg btn-whats" />
    </div>
</div>

{{-- Informações de atendimento --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="panel-card h-100">
            <div class="panel-body">
                <div class="d-flex align-items-center gap-2 mb-1" style="color:#1a3a5c;">
                    <i class="bi bi-telephone"></i> <strong>Telefones</strong>
                </div>
                <p class="mb-0 text-muted">
                    {{ config('paroquia.telefone') }}<br>
                    {{ config('paroquia.celular') }} <span class="small">(WhatsApp)</span>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel-card h-100">
            <div class="panel-body">
                <div class="d-flex align-items-center gap-2 mb-1" style="color:#1a3a5c;">
                    <i class="bi bi-clock"></i> <strong>Atendimento</strong>
                </div>
                <p class="mb-0 text-muted">{{ config('paroquia.atendimento.dias') }}<br>{{ config('paroquia.atendimento.horario') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel-card h-100">
            <div class="panel-body">
                <div class="d-flex align-items-center gap-2 mb-1" style="color:#1a3a5c;">
                    <i class="bi bi-geo-alt"></i> <strong>Endereço</strong>
                </div>
                <p class="mb-0 text-muted">
                    {{ config('paroquia.endereco.logradouro') }}, {{ config('paroquia.endereco.numero') }}, {{ config('paroquia.endereco.bairro') }}<br>
                    {{ config('paroquia.endereco.cep') }} · {{ config('paroquia.endereco.cidade') }}, {{ config('paroquia.endereco.estado') }}
                </p>
                <a href="{{ config('paroquia.mapa.rota') }}" target="_blank" rel="noopener"
                   class="small text-decoration-none d-inline-block mt-2">
                    <i class="bi bi-map"></i> Como chegar
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Redes sociais: Instagram da paróquia e do Grupo de Dança --}}
<div class="panel-card mb-2">
    <div class="panel-head"><i class="bi bi-share"></i> Onde mais nos encontrar</div>
    <div class="panel-body">
        <div class="row g-3">
            <div class="col-md-6">
                <a href="{{ config('paroquia.redes.facebook') }}" target="_blank" rel="noopener"
                   class="d-flex align-items-center gap-3 text-decoration-none">
                    <span class="topbar-icon"><i class="bi bi-facebook"></i></span>
                    <span>
                        <strong style="color:#1a3a5c;">Paróquia N. S. da Glória</strong><br>
                        <span class="text-muted small">{{ config('paroquia.redes.facebook_user') }}</span>
                    </span>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ config('paroquia.redes.instagram') }}" target="_blank" rel="noopener"
                   class="d-flex align-items-center gap-3 text-decoration-none">
                    <span class="topbar-icon"><i class="bi bi-instagram"></i></span>
                    <span>
                        <strong style="color:#1a3a5c;">Paróquia N. S. da Glória</strong><br>
                        <span class="text-muted small">{{ config('paroquia.redes.instagram_user') }}</span>
                    </span>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ config('paroquia.redes.instagram_danca') }}" target="_blank" rel="noopener"
                   class="d-flex align-items-center gap-3 text-decoration-none">
                    <span class="topbar-icon"><i class="bi bi-instagram"></i></span>
                    <span>
                        <strong style="color:#1a3a5c;">Grupo de Dança Kyiv</strong><br>
                        <span class="text-muted small">@folclorekyivpitanga</span>
                    </span>
                </a>
            </div>
            <div class="col-md-6">
                <a href="mailto:{{ config('paroquia.email') }}"
                   class="d-flex align-items-center gap-3 text-decoration-none">
                    <span class="topbar-icon"><i class="bi bi-envelope"></i></span>
                    <span>
                        <strong style="color:#1a3a5c;">E-mail da secretaria</strong><br>
                        <span class="text-muted small">{{ config('paroquia.email') }}</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

<p class="text-muted small text-center mb-0">
    {{ config('paroquia.nome') }} · CNPJ {{ config('paroquia.cnpj') }}
</p>

@endsection
