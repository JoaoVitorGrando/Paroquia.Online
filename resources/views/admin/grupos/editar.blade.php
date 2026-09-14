@extends('layouts.app')

@section('title', 'Editar Grupo')

@section('content')
<div class="admin-topbar d-flex flex-wrap align-items-center gap-3">
    <span class="topbar-icon"><i class="bi bi-pencil-square"></i></span>
    <div class="me-auto">
        <h2>Editar grupo</h2>
        <p class="topbar-sub">Atualize as informações e a foto do grupo</p>
    </div>
    <a href="{{ route('admin.grupos') }}" class="btn btn-sm btn-outline-light">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="panel-card">
    <div class="panel-body">
        <form action="{{ route('admin.grupos.atualizar', $grupo->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nome do grupo *</label>
                <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                       value="{{ old('nome', $grupo->nome) }}" required>
                @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição *</label>
                <textarea name="descricao" rows="3" class="form-control @error('descricao') is-invalid @enderror"
                          required>{{ old('descricao', $grupo->descricao) }}</textarea>
                @error('descricao')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Responsável</label>
                    <input type="text" name="responsavel" class="form-control"
                           value="{{ old('responsavel', $grupo->responsavel) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Local</label>
                    <input type="text" name="local" class="form-control" value="{{ old('local', $grupo->local) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Dia da reunião</label>
                    <select name="dia_reuniao" class="form-select">
                        <option value="">Selecione...</option>
                        @foreach(['Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado','Domingo'] as $d)
                            <option value="{{ $d }}" {{ old('dia_reuniao', $grupo->dia_reuniao) === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Horário</label>
                    <input type="time" name="horario_reuniao" class="form-control"
                           value="{{ old('horario_reuniao', $grupo->horario_reuniao ? \Carbon\Carbon::parse($grupo->horario_reuniao)->format('H:i') : '') }}">
                </div>
            </div>

            {{-- Foto do grupo (opcional) --}}
            @if($grupo->imagem)
                <div class="mb-3">
                    <label class="form-label d-block">Foto atual</label>
                    <img src="{{ asset($grupo->imagem) }}" alt="Foto do grupo {{ $grupo->nome }}"
                         class="rounded border" style="max-height:160px;">
                    <div class="form-check mt-2">
                        <input type="checkbox" name="remover_imagem" class="form-check-input" id="remover_imagem">
                        <label class="form-check-label" for="remover_imagem">Remover a foto atual</label>
                    </div>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">{{ $grupo->imagem ? 'Trocar foto' : 'Foto do grupo' }}</label>
                <input type="file" name="imagem" accept="image/*"
                       class="form-control @error('imagem') is-invalid @enderror">
                <div class="form-text">JPG, PNG ou WEBP, até 2 MB.</div>
                @error('imagem')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn text-white" style="background-color:#1a3a5c;">
                <i class="bi bi-save"></i> Salvar alterações
            </button>
        </form>
    </div>
</div>
@endsection
