@extends('layouts.app')

@section('title', 'Panel Estudiante')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Panel Estudiante</h2>
            <p class="text-muted mb-0">Sigue tu ritmo de estudio y revisa tus próximos cursos y tareas.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card section-card h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Cursos registrados</h6>
                    <h3 class="mb-0">{{ $courses->count() }}</h3>
                    <p class="text-muted mb-0">Cursos recientes en los que estás matriculado.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card section-card h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Próxima clase</h6>
                    @if($nextClass)
                    <h3 class="mb-0">{{ $nextClass->titulo }}</h3>  
                    <p class="text-muted mb-0">{{ $nextClass->fecha }} · {{ $nextClass->inicio }}</p>
                    @else
                    <h3 class="mb-0">Sin clase próxima</h3>
                    <p class="text-muted mb-0">Revisa el calendario para programar tu próxima sesión.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card section-card h-100">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Tareas pendientes</h6>
                    <h3 class="mb-0">{{ $tasksPending }}</h3>
                    <p class="text-muted mb-0">Recuerda completar tus evaluaciones antes de la fecha límite.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Mis cursos</h4>
        <a href="#" class="text-primary text-decoration-none">Ver todos</a>
    </div>

    <div class="row g-4">
        @forelse ($courses as $course)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $course->titulo }}</h5>
                    <p class="text-muted small mb-3">{{ \Illuminate\Support\Str::limit($course->descripcion, 85) }}</p>
                    <span class="badge bg-secondary mb-3">{{ $course->modalidad }}</span>
                    <p class="mb-0"><strong>Inscrito:</strong> {{ $course->fechamatricula }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-light">No hay cursos inscritos en este momento.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection