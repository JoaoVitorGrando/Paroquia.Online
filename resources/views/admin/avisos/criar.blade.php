@extends('layouts.app')

@section('title', 'Novo Aviso')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <h2 class="mb-4"><i class="bi bi-megaphone"></i> Publicar Aviso</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.avisos.salvar') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Conteúdo *</label>
                        <textarea name="conteudo" class="form-control" rows="5" required>{{ old('conteudo') }}</textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="destaque" class="form-check-input" id="destaque">
                        <label class="form-check-label" for="destaque">Marcar como destaque (aparece na página inicial)</label>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn text-white" style="background-color:#1a3a5c;">
                            <i class="bi bi-check-circle"></i> Publicar
                        </button>
                        <a href="{{ route('admin.avisos') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
