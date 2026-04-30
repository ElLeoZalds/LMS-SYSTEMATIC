@extends('layouts.app')

@section('title', 'Panel Administrador')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Panel Administrador</h2>
            <p class="text-muted mb-0">Gestiona.</p>
        </div>
    </div>
    <div class="p-4 bg-white rounded-4">
        <div class="row mb-4">
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card p-3 text-center card-hover h-100">
                    <h6>Cursos totales</h6>
                    <h3>{{ $courses }}</h3>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card p-3 text-center card-hover h-100">
                    <h6>Estudiantes</h6>
                    <h3>{{ $students }}</h3>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card p-3 text-center card-hover h-100">
                    <h6>Tareas pendientes</h6>
                    <h3>{{ $pendingTasks }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center card-hover h-100">
                    <h6>Progreso promedio</h6>
                    <h3>{{ $averageProgress }}%</h3>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
            <h5 class="mb-0">Actividad reciente</h5>
            <a href="#" class="text-decoration-none">Ver todo</a>
        </div>

        <div class="row gy-4">
            @foreach($recentUsers as $user)
            <div class="col-md-3">
                <div class="card card-hover h-100">
                    <div class="card-body">
                        <h6 class="mb-2">{{ $user->nombres }} {{ $user->apellidos }}</h6>
                        <p class="text-muted small mb-2">{{ $user->username }}</p>
                        <p class="mb-0 small text-muted">Creado: {{ \Carbon\Carbon::parse($user->fechacreacion)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection