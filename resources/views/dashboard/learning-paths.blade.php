@extends('layouts.app')

@section('title', 'Rutas de aprendizaje')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2>Rutas de aprendizaje</h2>
            <p class="text-muted">Explora las especialidades y sigue las rutas formativas que mejor se adapten a tus objetivos.</p>
        </div>
        <a href="#" class="btn btn-primary btn-sm">Crear nueva ruta</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Especialidades</h5>
                    <ul class="list-group list-group-flush">
                        @foreach($specialties as $specialty)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $specialty->especialidad }}
                            <span class="badge bg-primary rounded-pill">{{ $specialty->cursos_count }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Cursos recomendados</h5>
                        <span class="text-muted">Basado en tus especialidades</span>
                    </div>
                    <div class="row g-3">
                        @foreach($topPaths as $course)
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100">
                                <h6>{{ $course->titulo }}</h6>
                                <p class="text-muted small mb-0">{{ \Illuminate\Support\Str::limit($course->descripcion, 90) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection