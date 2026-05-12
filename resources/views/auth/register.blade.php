@extends('layouts.auth')

@section('title', 'Registro - Systematic')

@section('content')

    <div class="container d-flex align-items-center justify-content-center min-vh-100">

        <div class="col-md-5">

            <div class="text-center mb-4">
                <img src="{{ asset('images/Systematic_logo.png') }}" width="140">
                <h4 class="mt-3 brand">Crea tu cuenta</h4>
                <p class="text-muted">Regístrate para acceder a tus cursos y evaluaciones.</p>
            </div>

            <div class="card login-card p-4">

                <form method="POST" action="{{ route('register.submit') }}">

                    @csrf

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-control" placeholder="Nombre completo" required>
                        @error('full_name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nombre@ejemplo.com" required>
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="********" required>
                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="********" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Registrarse
                    </button>

                </form>

            </div>

            <p class="text-center mt-3 text-muted">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
            </p>

        </div>
    </div>

@endsection