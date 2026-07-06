@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-1">
    <div class="mb-4">
        <h1 class="h3 mb-2 text-gray-800 fw-bold">Mis Cursos</h1>
        <p class="text-muted mb-0">Consulta el listado completo de tus capacitaciones matriculadas y continúa desde donde lo dejaste.</p>
    </div>

    @if($courses->isEmpty())
        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-journal-bookmark text-primary" style="font-size: 3rem;"></i>
                <h5 class="card-title mt-4 text-dark fw-bold">Aún no tienes cursos inscritos</h5>
                <p class="text-muted">Cuando te inscribas en una capacitación, aparecerá aquí para que puedas comenzar tu aprendizaje.</p>
                <a href="{{ route('student.dashboard') }}" class="btn btn-primary mt-3">Volver al inicio</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($courses as $enrollment)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 shadow-sm rounded-3 border-0 overflow-hidden course-card-clickable" data-url="{{ route('student.courses.show', $enrollment->training?->training_id) }}">
                        <div class="course-banner position-relative">
                            @if(!empty($enrollment->training?->course?->banner_path))
                                <div class="course-banner-image" style="background-image: url('{{ asset('storage/'.$enrollment->training->course->banner_path) }}?v={{ file_exists(storage_path('app/public/'.$enrollment->training->course->banner_path)) ? filemtime(storage_path('app/public/'.$enrollment->training->course->banner_path)) : time() }}');"></div>
                            @else
                                <div class="course-banner-fallback d-flex align-items-center justify-content-center text-white">
                                    {{ strtoupper(substr($enrollment->training?->course?->title ?? 'C', 0, 1)) }}
                                </div>
                            @endif
                            <div class="course-banner-overlay"></div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div>
                                <h5 class="card-title fw-bold text-dark mb-2 line-clamp-2" style="font-size: 1.1rem;">
                                    {{ $enrollment->training?->course?->title ?? 'Curso no asignado' }}
                                </h5>
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-person-fill me-1"></i>
                                    <strong class="text-dark">
                                        @if($enrollment->training?->teacher?->person)
                                            {{ $enrollment->training->teacher->person->first_names }} {{ $enrollment->training->teacher->person->last_names }}
                                        @else
                                            Por asignar
                                        @endif
                                    </strong>
                                </p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-laptop me-1"></i>{{ ucfirst($enrollment->training?->modality ?? 'N/A') }}
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-top flex-grow-1 d-flex flex-column justify-content-end">
                                <div class="d-flex justify-content-between align-items-center text-muted small mb-1">
                                    <span>Progreso</span>
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
                                <span class="badge {{ $enrollment->isCompleted() ? 'bg-secondary' : 'bg-success' }} text-white rounded-2 px-2 py-1 small">
                                    {{ $enrollment->isCompleted() ? 'Finalizado' : 'En curso' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .card { transition: box-shadow 0.3s ease, transform 0.3s ease; }
    .card:hover { box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.12) !important; transform: translateY(-4px); }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .course-banner { height: 160px; position: relative; overflow: hidden; background: #e9ecef; }
    .course-banner-image { background-size: cover; background-position: center; width: 100%; height: 100%; }
    .course-banner-fallback { background: #0d6efd; width: 100%; height: 100%; font-size: 2.5rem; font-weight: bold; opacity: 0.9; }
    .course-banner-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.06); }
    .course-card-clickable { cursor: pointer; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.course-card-clickable').forEach(function (card) {
            card.addEventListener('click', function () {
                const url = card.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
@endsection