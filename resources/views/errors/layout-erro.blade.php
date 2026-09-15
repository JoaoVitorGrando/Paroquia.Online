@extends('layouts.app')

@section('title', $titulo)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body p-4 p-md-5">
                <i class="bi {{ $icone }} display-4 text-secondary" aria-hidden="true"></i>

                <h1 class="h3 mt-3 mb-2">{{ $titulo }}</h1>
                <p class="text-muted mb-4">{{ $mensagem }}</p>

                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="bi bi-house-door" aria-hidden="true"></i> Ir para a página inicial
                    </a>
                    <a href="{{ route('contato') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-telephone" aria-hidden="true"></i> Falar com a secretaria
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
