@extends('layouts.auth')

@section('title', 'Login - Systematic')

@section('content')

    <div class="container d-flex align-items-center justify-content-center min-vh-100">

        <div class="col-md-5">

            <div class="text-center mb-4">
                <img src="{{ asset('images/Systematic_logo.png') }}" width="140">
                <h4 class="mt-3 brand">Bienvenido a Systematic</h4>
                <p class="text-muted">Inicia sesión para continuar</p>
            </div>

            <div class="card login-card p-4">

                <form method="POST" action="{{ route('login') }}">

                    @csrf

                    {{-- ALERTA GENERAL --}}
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info">
                            {{ session('info') }}
                        </div>
                    @endif

                    {{-- USERNAME --}}
                    <div class="mb-3">
                        <label class="form-label">Usuario</label>

                        <input type="text" name="username" value="{{ old('username') }}" class="form-control"
                            placeholder="usuario" required>

                        @error('username')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>

                        <input type="password" name="password" class="form-control" placeholder="********" required>

                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Iniciar Sesión
                    </button>

                </form>

            </div>

            <p class="text-center mt-3 text-muted">
                ¿No tienes cuenta? <a href="#">Regístrate</a>
            </p>

        </div>
    </div>

@endsection