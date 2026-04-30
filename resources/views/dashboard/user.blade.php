@extends('layouts.app')

@section('title', 'Perfil de usuario')

@section('content')
<div class="container-fluid mt-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h2>Perfil de usuario</h2>
                    <p class="text-muted">Ver y actualizar tu información de perfil aquí.</p>

                    @if($user)
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <strong>Nombre completo</strong>
                            <p class="mb-0">{{ $user->nombres }} {{ $user->apellidos }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Usuario</strong>
                            <p class="mb-0">{{ $user->username }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Email</strong>
                            <p class="mb-0">{{ $user->email ?? 'No registrado' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Teléfono</strong>
                            <p class="mb-0">{{ $user->telefono ?? 'No registrado' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Dirección</strong>
                            <p class="mb-0">{{ $user->direccion ?? 'No registrada' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Fecha de creación</strong>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($user->fechacreacion)->format('d M Y') }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-muted">No se encontraron datos de usuario.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5>Resumen</h5>
                    <p class="mb-3 text-muted">Mira tus métricas principales y el estado actual de tu cuenta.</p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Cursos activos</span>
                        <strong>{{ $activeEnrollments }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Evaluaciones pendientes</span>
                        <strong>{{ $pendingEvaluations }}</strong>
                    </div>
                    <a href="#" class="btn btn-primary btn-sm w-100">Editar perfil</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection