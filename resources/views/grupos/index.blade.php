@extends('layouts.app')

@section('title', 'Grupos da Paróquia')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
    <div class="me-auto">
        <h1>Grupos e pastorais</h1>
        <p class="topbar-sub">Conheça e participe das atividades da nossa comunidade</p>
    </div>
</div>

@if($grupos->isNotEmpty())
    <h2 class="visually-hidden">Grupos ativos</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
        @foreach($grupos as $grupo)
            <div class="col"><x-card-grupo :grupo="$grupo" :resumo="160" /></div>
        @endforeach
    </div>
@else
    <x-vazio icone="bi-people" titulo="Nenhum grupo ativo no momento"
             texto="Fale com a secretaria para saber sobre as pastorais e os grupos da paróquia.">
        <x-whatsapp-btn
            mensagem="Olá, vim pelo site da paróquia e gostaria de saber sobre os grupos e pastorais."
            rotulo="Perguntar pelo WhatsApp" />
    </x-vazio>
@endif
@endsection
