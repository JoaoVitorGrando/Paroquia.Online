@extends('layouts.app')

@section('title', 'Contato')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-envelope"></i></span>
    <div class="me-auto">
        <h2>Entre em contato</h2>
        <p class="topbar-sub">Fale com a secretaria paroquial</p>
    </div>
</div>

{{-- WhatsApp em destaque: canal principal pedido pela paróquia --}}
<div class="panel-card mb-4" style="background-color:#eafaf0; border-color:#bde7cc;">
    <div class="panel-body d-flex flex-wrap align-items-center gap-3">
        <i class="bi bi-whatsapp" style="font-size:2.6rem; color:#25d366;"></i>
        <div class="me-auto">
            <h5 class="mb-1" style="color:#0a3d1f;">Fale com a secretaria pelo WhatsApp</h5>
            <p class="mb-0 small text-muted">
                É o jeito mais rápido de tirar dúvidas sobre missas, inscrições, batizados e casamentos.
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
                    <i class="bi bi-telephone"></i> <strong>Telefone</strong>
                </div>
                <p class="mb-0 text-muted">{{ config('paroquia.telefone') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel-card h-100">
            <div class="panel-body">
                <div class="d-flex align-items-center gap-2 mb-1" style="color:#1a3a5c;">
                    <i class="bi bi-clock"></i> <strong>Atendimento</strong>
                </div>
                <p class="mb-0 text-muted">Segunda a sexta<br>09h às 12h e 14h às 17h</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel-card h-100">
            <div class="panel-body">
                <div class="d-flex align-items-center gap-2 mb-1" style="color:#1a3a5c;">
                    <i class="bi bi-geo-alt"></i> <strong>Endereço</strong>
                </div>
                <p class="mb-0 text-muted">Rua Conselheiro Zacarias, 295<br>85200-053, Pitanga, Paraná</p>
            </div>
        </div>
    </div>
</div>

{{-- Redes sociais: Instagram da paróquia e do Grupo de Dança --}}
<div class="panel-card mb-2">
    <div class="panel-head"><i class="bi bi-instagram"></i> Nos siga nas redes sociais</div>
    <div class="panel-body">
        <div class="row g-3">
            <div class="col-md-6">
                <a href="https://www.instagram.com/pnsg_1/" target="_blank" rel="noopener"
                   class="d-flex align-items-center gap-3 text-decoration-none">
                    <span class="topbar-icon"><i class="bi bi-instagram"></i></span>
                    <span>
                        <strong style="color:#1a3a5c;">Paróquia N. S. da Glória</strong><br>
                        <span class="text-muted small">@pnsg_1</span>
                    </span>
                </a>
            </div>
            <div class="col-md-6">
                <a href="https://www.instagram.com/folclorekyivpitanga/" target="_blank" rel="noopener"
                   class="d-flex align-items-center gap-3 text-decoration-none">
                    <span class="topbar-icon"><i class="bi bi-instagram"></i></span>
                    <span>
                        <strong style="color:#1a3a5c;">Grupo de Dança Kyiv</strong><br>
                        <span class="text-muted small">@folclorekyivpitanga</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
