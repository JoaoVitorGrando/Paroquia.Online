@extends('layouts.app')

@section('title', 'Cadastro')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm mt-3">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h5 class="mb-0"><i class="bi bi-person-plus"></i> Criar conta</h5>
            </div>
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $erro)
                                <li>{{ $erro }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('cadastro.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome completo</label>
                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Seu nome"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="seuemail@exemplo.com"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Mínimo 6 caracteres"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar senha</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Repita a senha"
                            required
                        >
                    </div>

                    <button type="submit" class="btn w-100 text-white" style="background-color: #1a3a5c;">
                        <i class="bi bi-check-circle"></i> Cadastrar
                    </button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    Já tem conta? <a href="{{ route('login') }}">Faça login</a>
                </p>

            </div>
        </div>
    </div>
</div>
@endsection
