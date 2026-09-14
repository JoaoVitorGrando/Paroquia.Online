@extends('layouts.app')

@section('title', 'Sacramentos')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-heart"></i></span>
    <div class="me-auto">
        <h2>Batizados e casamentos</h2>
        <p class="topbar-sub">Como agendar e quais documentos são necessários</p>
    </div>
</div>

{{-- Aviso em destaque: não dá para marcar online --}}
<div class="alert alert-warning d-flex gap-3" role="alert">
    <i class="bi bi-info-circle-fill fs-5"></i>
    <div>
        <strong>O agendamento é feito pessoalmente na secretaria paroquial.</strong><br>
        Batizados e casamentos envolvem preparação e documentação, por isso a conversa com o pároco
        é sempre presencial. Use o WhatsApp para marcar essa conversa e tirar dúvidas iniciais.
    </div>
</div>

@php
    $sacramentos = [
        [
            'chave'  => 'batizado',
            'titulo' => 'Batizado',
            'icone'  => 'bi-droplet',
            'texto'  => 'O batismo é a porta de entrada na vida cristã. Os pais e padrinhos participam de uma preparação antes da celebração.',
            'passos' => [
                'Procure a secretaria paroquial para informar o interesse.',
                'Entregue a documentação dos pais, do padrinho e da madrinha.',
                'Participe do encontro de preparação para pais e padrinhos.',
                'Confirme a data da celebração com a secretaria.',
            ],
            'documentos' => [
                'Certidão de nascimento da criança',
                'Documento com foto dos pais',
                'Documento com foto dos padrinhos',
                'Comprovante de endereço',
            ],
        ],
        [
            'chave'  => 'casamento',
            'titulo' => 'Casamento',
            'icone'  => 'bi-heart',
            'texto'  => 'O casamento exige preparação dos noivos e reserva antecipada da data. Procure a paróquia com bastante antecedência para organizar tudo com calma.',
            'passos' => [
                'Procure a secretaria para conversar com o pároco.',
                'Reserve a data e o horário da celebração.',
                'Participe do curso de preparação para noivos.',
                'Entregue a documentação completa no prazo combinado.',
                'Celebração do matrimônio.',
            ],
            'documentos' => [
                'Certidão de batismo atualizada dos noivos',
                'Documento com foto e CPF dos noivos',
                'Comprovante de endereço',
                'Certificado do curso de noivos',
                'Documento das testemunhas',
            ],
        ],
    ];
@endphp

<div class="row g-4">
    @foreach($sacramentos as $s)
        <div class="col-lg-6">
            <div class="panel-card h-100">
                <div class="panel-head" style="background-color:#1a3a5c; color:#fff;">
                    <i class="bi {{ $s['icone'] }}"></i> {{ $s['titulo'] }}
                </div>
                <div class="panel-body d-flex flex-column h-100">
                    <p class="text-muted">{{ $s['texto'] }}</p>

                    <h6 style="color:#1a3a5c;">Passo a passo</h6>
                    <ol class="list-unstyled mb-3">
                        @foreach($s['passos'] as $i => $passo)
                            <li class="d-flex align-items-start gap-3 mb-2">
                                <span class="passo-num">{{ $i + 1 }}</span>
                                <span>{{ $passo }}</span>
                            </li>
                        @endforeach
                    </ol>

                    <h6 style="color:#1a3a5c;">Documentos</h6>
                    <ul class="mb-4">
                        @foreach($s['documentos'] as $doc)
                            <li>{{ $doc }}</li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        <x-whatsapp-btn
                            :mensagem="'Olá, vim pelo site da paróquia e gostaria de agendar uma conversa sobre *' . $s['chave'] . '*.'"
                            :rotulo="'Agendar conversa sobre ' . $s['chave']"
                            classe="btn btn-whats w-100" />
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Rodapé da página: atendimento --}}
<div class="panel-card mt-4">
    <div class="panel-body d-flex flex-wrap align-items-center gap-3 small text-muted">
        <span><i class="bi bi-clock"></i> Atendimento: segunda a sexta, 09h às 12h e 14h às 17h</span>
        <span><i class="bi bi-telephone"></i> {{ config('paroquia.telefone') }}</span>
        <span><i class="bi bi-geo-alt"></i> Caixa Postal, 10 — Pitanga/PR</span>
        <a href="{{ route('contato') }}" class="btn btn-sm btn-outline-secondary ms-auto">
            <i class="bi bi-envelope"></i> Outras formas de contato
        </a>
    </div>
</div>

<p class="text-muted small mt-3 mb-0">
    <i class="bi bi-info-circle"></i>
    Prazos, documentos e datas disponíveis devem ser confirmados diretamente com a secretaria paroquial.
</p>
@endsection
