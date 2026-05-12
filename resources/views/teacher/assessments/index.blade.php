@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="mb-4">
            <h1 class="h3 mb-2 text-gray-800">Mis Evaluaciones</h1>
            <p class="text-muted">Selecciona una capacitación para gestionar sus evaluaciones.</p>
        </div>

        @if($trainings->count() > 0)
            <div class="row g-4">
                @foreach($trainings as $training)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">{{ $training->course->title }}</h5>
                                <p class="text-muted small mb-3">Código: {{ $training->course->code ?? 'N/A' }}</p>
                                <p class="mb-3">
                                    <span class="badge bg-info">{{ ucfirst($training->modality) }}</span>
                                    <span class="badge bg-secondary">{{ $training->assessments->count() }} evaluaciones</span>
                                </p>
                                <div class="mt-auto">
                                    <a href="{{ route('teacher.assessments.show', $training->training_id) }}" class="btn btn-primary btn-sm w-100">
                                        Gestionar evaluaciones
                                    </a>
                                </div>
                            </div>
                        </div>
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
@endsection
