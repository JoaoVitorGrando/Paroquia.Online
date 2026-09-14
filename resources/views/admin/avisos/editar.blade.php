@extends('layouts.app')

@section('title', 'Editar Aviso')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-pencil-square"></i></span>
    <div class="me-auto">
        <h2>Editar aviso</h2>
        <p class="topbar-sub">Altere o texto ou o destaque do comunicado</p>
    </div>
    <a href="{{ route('admin.avisos') }}" class="btn btn-sm btn-outline-light">
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
                <form action="{{ route('admin.avisos.atualizar', $aviso->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror"
                               value="{{ old('titulo', $aviso->titulo) }}" required>
                        @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Conteúdo *</label>
                        <textarea name="conteudo" class="form-control @error('conteudo') is-invalid @enderror"
                                  rows="5" required>{{ old('conteudo', $aviso->conteudo) }}</textarea>
                        @error('conteudo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="destaque" class="form-check-input" id="destaque"
                               {{ old('destaque', $aviso->destaque) ? 'checked' : '' }}>
                        <label class="form-check-label" for="destaque">
                            Marcar como destaque (aparece na página inicial)
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn text-white" style="background-color:#1a3a5c;">
                            <i class="bi bi-check-circle"></i> Atualizar
                        </button>
                        <a href="{{ route('admin.avisos') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
