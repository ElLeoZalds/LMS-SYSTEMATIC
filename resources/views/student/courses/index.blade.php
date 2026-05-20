@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-1">
    <div class="mb-5">
        <h1 class="h3 mb-2 text-gray-800 font-weight-bold">Mis Capacitaciones</h1>
        <p class="text-muted">Accede a tus cursos activos y haz un seguimiento de tu progreso académico.</p>
    </div>

    @if($courses->isEmpty())
        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-4 text-dark fw-bold">Sin matrículas activas</h5>
                <p class="text-muted">Actualmente no te encuentras registrado en ninguna capacitación. Contacta con el administrador.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($courses as $enrollment)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="{{ url('/student/courses/' . ($enrollment->training?->training_id ?? 1)) }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm rounded-3 border-0 position-relative overflow-hidden transition-all">

                            <div class="bg-primary bg-gradient p-4 text-white d-flex align-items-center justify-content-center position-relative"
                                style="height: 120px; font-size: 2.5rem; font-weight: bold; opacity: 0.9;">
                                {{ strtoupper(substr($enrollment->training?->course?->name ?? 'C', 0, 1)) }}
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div>
                                    <h5 class="card-title fw-bold text-dark mb-2 line-clamp-2" style="font-size: 1.1rem;">
                                        {{ $enrollment->training?->course?->name ?? 'Curso no asignado' }}
                                    </h5>
                                    <p class="text-muted small mb-1">
                                        Modalidad: <strong class="text-dark capitalize">{{ $enrollment->training?->modality ?? 'N/A' }}</strong>
                                    </p>
                                    <p class="text-muted small mb-0">
                                        Instructor: 
                                        <strong class="text-dark">
                                            @if($enrollment->training?->teacher?->person)
                                                {{ $enrollment->training->teacher->person->first_names }} 
                                                {{ $enrollment->training->teacher->person->last_names }}
                                            @else
                                                <span class="text-muted italic fw-normal">Por asignar</span>
                                            @endif
                                        </strong>
                                    </p>
                                </div>

                                <div class="mt-4 pt-3 border-top flex-grow-1 d-flex flex-column justify-content-end">
                                    <div class="d-flex justify-content-between align-items-center text-muted small mb-1">
                                        <span>Progreso de aprendizaje</span>
                                        <span class="fw-bold text-primary bg-light px-2 py-0.5 rounded">
                                            {{ $enrollment->progress_percentage }}%
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary bg-gradient rounded-full" 
                                             role="progressbar" 
                                             style="width: {{ $enrollment->progress_percentage }}%; transition: width 0.5s ease;" 
                                             aria-valuenow="{{ $enrollment->progress_percentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-2 px-2 py-1.5 small border border-success border-opacity-25">
                                        ✓ Matriculado
                                    </span>
                                    <span class="text-muted small fw-bold">
                                        S/. {{ number_format((float)($enrollment->training?->price ?? 0), 2) }}
                                    </span>
                                </div>
                            </div>

                            <div class="card-footer bg-light border-top p-3 text-end">
                                <span class="text-muted small text-start float-start pt-1" style="font-size: 0.75rem;">
                                    ID: #{{ $enrollment->training?->training_id ?? 1 }}
                                </span>
                                <i class="bi bi-arrow-right text-primary align-middle" style="font-size: 1.1rem; line-height: 1;"></i>
                            </div>

                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .card { transition: box-shadow 0.3s ease, transform 0.3s ease; }
    .card:hover { box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.12) !important; transform: translateY(-4px); }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    a { color: inherit; text-decoration: none !important; }
    a:hover { color: inherit; }
</style>
@endsection