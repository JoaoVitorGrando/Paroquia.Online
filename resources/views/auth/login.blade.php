@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm mt-3">
            <div class="card-header text-white" style="background-color: #1a3a5c;">
                <h5 class="mb-0"><i class="bi bi-person"></i> Entrar na conta</h5>
            </div>
            <div class="card-body">

                @if(session('erro'))
                    <div class="alert alert-danger">
                        {{ session('erro') }}
                    </div>
                @endif

                @if(session('sucesso'))
                    <div class="alert alert-success">
                        {{ session('sucesso') }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

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
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Sua senha"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn w-100 text-white" style="background-color: #1a3a5c;">
                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                    </button>
                </form>

                <hr>
                <p class="text-center mb-0">
                    Não tem conta? <a href="{{ route('cadastro.form') }}">Cadastre-se</a>
                </p>

            </div>
        </div>
    </div>
</div>
@endsection
