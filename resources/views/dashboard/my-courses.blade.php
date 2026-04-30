@extends('layouts.app')

@section('title', 'Mis cursos')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-column flex-md-row">
        <div>
            <h2>Mis cursos</h2>
            <p class="text-muted">Revisa todos los cursos en los que estás inscrito aquí.</p>
        </div>
        <div class="text-muted">Inscripciones activas: {{ $enrollmentsCount }}</div>
    </div>

    <div class="row g-4">
        @forelse($courses as $course)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $course->titulo }}</h5>
                    <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($course->descripcion, 100) }}</p>
                    <div class="mb-3">
                        <span class="badge bg-secondary">{{ $course->modalidad }}</span>
                        <span class="badge bg-light text-dark">{{ $course->cantidadhoras ?? '—' }} h</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted">Inscrito: {{ $course->fechamatricula }}</span>
                        <strong>S/ {{ number_format($course->precioreferencial ?? 0, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-light">No hay cursos inscritos.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection