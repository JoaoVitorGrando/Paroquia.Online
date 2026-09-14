@extends('layouts.app')

@section('title', 'Editar Evento')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-pencil-square"></i></span>
    <div class="me-auto">
        <h2>Editar evento</h2>
        <p class="topbar-sub">Atualize as informações e a foto do evento</p>
    </div>
    <a href="{{ route('admin.eventos') }}" class="btn btn-sm btn-outline-light">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="panel-card">
            <div class="panel-body">
                <form action="{{ route('admin.eventos.atualizar', $evento->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" class="form-control"
                            value="{{ old('titulo', $evento->titulo) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição *</label>
                        <textarea name="descricao" class="form-control" rows="4" required>{{ old('descricao', $evento->descricao) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data *</label>
                            <input type="date" name="data" class="form-control"
                                value="{{ old('data', \Carbon\Carbon::parse($evento->data)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Horário</label>
                            <input type="time" name="horario" class="form-control"
                                value="{{ old('horario', $evento->horario ? \Carbon\Carbon::parse($evento->horario)->format('H:i') : '') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Local</label>
                        <input type="text" name="local" class="form-control"
                            value="{{ old('local', $evento->local) }}">
                    </div>

                    {{-- Foto do evento (opcional) --}}
                    @if($evento->imagem)
                        <div class="mb-3">
                            <label class="form-label d-block">Foto atual</label>
                            <img src="{{ asset($evento->imagem) }}" alt="Foto do evento {{ $evento->titulo }}"
                                 class="rounded border" style="max-height:160px;">
                            <div class="form-check mt-2">
                                <input type="checkbox" name="remover_imagem" class="form-check-input" id="remover_imagem">
                                <label class="form-check-label" for="remover_imagem">Remover a foto atual</label>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">{{ $evento->imagem ? 'Trocar foto' : 'Foto do evento' }}</label>
                        <input type="file" name="imagem" accept="image/*"
                               class="form-control @error('imagem') is-invalid @enderror">
                        <div class="form-text">JPG, PNG ou WEBP, até 2 MB.</div>
                        @error('imagem')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn text-white" style="background-color:#1a3a5c;">
                            <i class="bi bi-check-circle"></i> Atualizar
                        </button>
                        <a href="{{ route('admin.eventos') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
