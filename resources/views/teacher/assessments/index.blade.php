@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="mb-4">
            <h1 class="h3 mb-2 text-gray-800 fw-bold">Mis Evaluaciones</h1>
            <p class="text-muted small">Selecciona una capacitación para gestionar sus evaluaciones.</p>
        </div>

        @if($trainings->count() > 0)
            <div class="row g-4">
                @foreach($trainings as $training)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <a href="{{ route('teacher.assessments.show', $training->training_id) }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm rounded-3 border-0 position-relative overflow-hidden transition-all">

                                <div class="bg-info bg-gradient p-4 text-white d-flex align-items-center justify-content-center"
                                    style="height: 120px; font-size: 2.5rem; font-weight: bold; opacity: 0.9;">
                                    {{ strtoupper(substr($training->course->title, 0, 1)) }}
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <div>
                                        <h5 class="card-title fw-bold text-dark mb-2 line-clamp-2" style="font-size: 1.1rem;">
                                            {{ $training->course->title }}
                                        </h5>
                                        <p class="text-muted small mb-0">Código: {{ $training->course->code ?? 'N/A' }}</p>
                                    </div>

                                    <div class="row text-center mt-4 pt-3 border-top g-2 flex-grow-1 align-items-end">
                                        <div class="col-6">
                                            <div class="h6 fw-bold text-info mb-1">
                                                {{ $training->assessments->count() }}
                                            </div>
                                            <small class="text-muted" style="font-size: 0.75rem;">Evaluaciones</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="h6 fw-bold text-secondary mb-1">
                                                {{ ucfirst($training->modality) }}
                                            </div>
                                            <small class="text-muted" style="font-size: 0.75rem;">Modalidad</small>
                                        </div>
                                    </div>

                                    <div class="mt-3 text-primary small fw-semibold">
                                        <i class="bi bi-gear-fill me-1"></i> Gestionar
                                    </div>
                                </div>

                                <div class="card-footer bg-light border-top p-3 text-end">
                                    <i class="bi bi-arrow-right text-muted" style="font-size: 1.1rem;"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard-x" style="font-size: 3rem;"></i>
                    <h5 class="mt-4 mb-2">No tienes capacitaciones asignadas</h5>
                    <p class="text-muted">Cuando tengas cursos, podrás crear evaluaciones desde aquí.</p>
                </div>
            </div>
        @endif
    </div>

    <style>
        .card { transition: box-shadow 0.3s ease, transform 0.3s ease; }
        .card:hover { box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important; transform: translateY(-4px); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        a { color: inherit; text-decoration: none !important; }
        a:hover { color: inherit; }
    </style>
@endsection