@extends('layouts.app')

@section('title', 'Novo Evento')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-plus-circle"></i></span>
    <div class="me-auto">
        <h2>Cadastrar evento</h2>
        <p class="topbar-sub">Preencha os dados do evento ou festa da comunidade</p>
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
                <form action="{{ route('admin.eventos.salvar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição *</label>
                        <textarea name="descricao" class="form-control" rows="4" required>{{ old('descricao') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data *</label>
                            <input type="date" name="data" class="form-control" value="{{ old('data') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Horário</label>
                            <input type="time" name="horario" class="form-control" value="{{ old('horario') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Local</label>
                        <input type="text" name="local" class="form-control" value="{{ old('local') }}">
                    </div>

                    {{-- Foto do evento (opcional) --}}
                    <div class="mb-3">
                        <label class="form-label">Foto do evento</label>
                        <input type="file" name="imagem" accept="image/*"
                               class="form-control @error('imagem') is-invalid @enderror">
                        <div class="form-text">JPG, PNG ou WEBP, até 2 MB. O evento também pode ficar sem foto.</div>
                        @error('imagem')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn text-white" style="background-color:#1a3a5c;">
                            <i class="bi bi-check-circle"></i> Salvar
                        </button>
                        <a href="{{ route('admin.eventos') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
