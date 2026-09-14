@extends('layouts.app')

@section('title', 'Editar Horário de Missa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Horário de Missa</h2>
    <a href="{{ route('admin.missas') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.missas.atualizar', $missa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Dia da semana *</label>
                    <select name="dia_semana" class="form-select @error('dia_semana') is-invalid @enderror" required>
                        @foreach(['Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado','Domingo'] as $d)
                            <option value="{{ $d }}" {{ old('dia_semana', $missa->dia_semana) === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                    @error('dia_semana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Horário *</label>
                    <input type="time" name="horario" class="form-control @error('horario') is-invalid @enderror"
                           value="{{ old('horario', \Carbon\Carbon::parse($missa->horario)->format('H:i')) }}" required>
                    @error('horario')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Local</label>
                <input type="text" name="local" class="form-control" value="{{ old('local', $missa->local) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Observação</label>
                <input type="text" name="observacao" class="form-control" value="{{ old('observacao', $missa->observacao) }}">
            </div>

            <button type="submit" class="btn text-white" style="background-color:#1a3a5c;">
                <i class="bi bi-save"></i> Salvar alterações
            </button>
        </form>
    </div>
</div>
@endsection
