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

            <form method="POST" action="/login">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="********">
                </div>

                <div class="mb-3">
                    <label class="form-label">Acceder como</label>
                    <select name="role" class="form-select">
                        <option value="student" selected>Estudiante</option>
                        <option value="teacher">Docente</option>
                        <option value="admin">Administrador</option>
                    </select>
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