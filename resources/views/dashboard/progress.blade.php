@extends('layouts.app')

@section('title', 'Progreso')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2>Progreso</h2>
            <p class="text-muted">Haz seguimiento de tu avance en cursos, evaluaciones y certificados.</p>
        </div>
        <div class="text-muted">Total de inscripciones: {{ $totalEnrollments }}</div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card section-card h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Evaluaciones activas</h6>
                    <h3 class="mb-0">{{ $activeEvaluations }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card section-card h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Certificados</h6>
                    <h3 class="mb-0">{{ $completedCertificates }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card section-card h-100 text-center">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Progreso general</h6>
                    <h3 class="mb-0">{{ round($completedCertificates / max(1, $totalEnrollments) * 100) }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Progreso por curso</h5>
            @if($courseProgress->isEmpty())
            <div class="text-muted">No hay datos de progreso disponibles.</div>
            @else
            <div class="list-group list-group-flush">
                @foreach($courseProgress as $course)
                <div class="list-group-item border-0 px-0 py-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>{{ $course->titulo }}</div>
                        <small class="text-muted">{{ $course->inscritos }} inscritos</small>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, $course->inscritos * 3) }}%;"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection