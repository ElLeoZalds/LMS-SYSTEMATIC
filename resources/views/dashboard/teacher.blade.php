@extends('layouts.app')

@section('title', 'Panel Docente')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Panel Docente</h2>
            <p class="text-muted mb-0">Gestiona tus cursos, alumnos y próximas clases desde un solo lugar.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card section-card h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Capacitaciones</h6>
                    <h3 class="mb-0">{{ $courseCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card section-card h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Estudiantes</h6>
                    <h3 class="mb-0">{{ $studentsCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card section-card h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Tareas activas</h6>
                    <h3 class="mb-0">{{ $pendingTasks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card section-card h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Progreso medio</h6>
                    <h3 class="mb-0">{{ $averageProgress }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Clases próximas</h5>
                        <a href="{{ route('dashboard.calendar') }}" class="text-decoration-none">Ver calendario</a>
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse($upcomingClasses as $session)
                        <li class="list-group-item py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $session->titulo }}</strong>
                                    <p class="mb-0 text-muted small">{{ $session->fecha }} • {{ $session->modalidad }}</p>
                                </div>
                                <span class="badge bg-primary align-self-center">{{ $session->inicio }}</span>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-muted">No hay clases próximas programadas.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Cursos con más estudiantes</h5>
                        <a href="#" class="text-decoration-none">Ver todo</a>
                    </div>
                    @foreach($topCourses as $course)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>{{ $course->titulo }}</div>
                            <span class="badge bg-secondary">{{ $course->inscritos }}</span>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, $course->inscritos * 2) }}%;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection