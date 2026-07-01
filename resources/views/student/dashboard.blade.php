@extends('layouts.app')

@section('content')

    <div class="container-fluid px-4 py-1">

        @if($hasEnrollments)
            <h1 class="h3 mb-4 text-gray-800 fw-bold"><i class="bi bi-mortarboard-fill me-2"></i>Resumen de Actividad</h1>

            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-book-fill text-primary h4 mb-2"></i>
                            <h5 class="h6 fw-bold text-gray-800">{{ $totalCourses }}</h5>
                            <small class="text-muted">Cursos inscritos</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-hourglass-split text-warning h4 mb-2"></i>
                            <h5 class="h6 fw-bold text-gray-800">{{ $inProgress }}</h5>
                            <small class="text-muted">En progreso</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle-fill text-success h4 mb-2"></i>
                            <h5 class="h6 fw-bold text-gray-800">{{ $completed }}</h5>
                            <small class="text-muted">Completados</small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mb-4">
                <h3 class="text-gray-800 fw-bold h5">
                    <i class="bi bi-journal-text me-2"></i>Mis Cursos Recientes
                </h3>
            </div>

            <div class="row g-4">

                @foreach($enrollments as $enrollment)

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm rounded-3 border-0 position-relative overflow-hidden transition-all course-card-clickable" data-url="{{ route('student.courses.show', $enrollment->training->training_id) }}">

                            <div class="course-banner position-relative">
                                @if(!empty($enrollment->training->course->banner_path))
                                    <div class="course-banner-image" style="background-image: url('{{ asset('storage/'.$enrollment->training->course->banner_path) }}?v={{ file_exists(storage_path('app/public/'.$enrollment->training->course->banner_path)) ? filemtime(storage_path('app/public/'.$enrollment->training->course->banner_path)) : time() }}'); height:120px;"></div>
                                @else
                                    <div class="course-banner-fallback d-flex align-items-center justify-content-center text-white"
                                        style="height:120px; font-size: 2.5rem; font-weight: bold; opacity: 0.9;">
                                        {{ strtoupper(substr($enrollment->training->course->title, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="course-banner-overlay"></div>
                            </div>

                            <div class="card-body d-flex flex-column p-4">
                                <div class="mb-3">
                                    <h5 class="card-title fw-bold text-dark mb-2 line-clamp-2" style="font-size: 1.1rem;">
                                        {{ optional($enrollment->training->course)->title ?? 'Sin curso' }}{{ optional($enrollment->training->start_date)->format(' (Y-m)') }}
                                    </h5>

                                    <div style="font-size: 0.8rem;">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-person-fill me-1"></i>{{ $enrollment->training->teacher->person->first_names ?? 'Sin profesor' }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-tag-fill me-1"></i>{{ ucfirst($enrollment->training->modality) }}
                                        </small>
                                    </div>
                                </div>

                                <div class="mb-3 pt-2 border-top flex-grow-1 d-flex flex-column justify-content-end">
                                    <div class="d-flex justify-content-between align-items-center text-muted small mb-1">
                                        <span>Progreso de aprendizaje</span>
                                        <span class="fw-bold text-primary">{{ $enrollment->progress_percentage }}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $enrollment->progress_percentage }}%"
                                            aria-valuenow="{{ $enrollment->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary text-white rounded-2 px-2 py-1 small">
                                        ✓ Matriculado
                                    </span>
                                    <span class="text-muted small fw-bold">
                                        {{ $enrollment->schedule_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="bi bi-mortarboard-fill text-primary fs-4"></i>
                        </div>
                        <div>
                            <h2 class="h4 fw-bold mb-1 text-gray-800">Hola, {{ $studentName ?: 'estudiante' }}</h2>
                            <p class="text-muted mb-0">Aún no estás inscrito en ningún curso.</p>
                        </div>
                    </div>

                    <h3 class="h5 fw-bold text-gray-800 mb-4">
                        <i class="bi bi-grid-fill me-2"></i>Explora nuestros cursos
                    </h3>

                    @if($featuredCourses->isNotEmpty())
                        <div class="row g-4">
                            @foreach($featuredCourses as $course)
                                @php
                                    $courseTraining = $course->trainings()->where('status', 1)->first();
                                @endphp

                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="card h-100 shadow-sm rounded-3 border-0 overflow-hidden">
                                        @if(!empty($course->banner_path))
                                            <img src="{{ asset('storage/'.$course->banner_path) }}?v={{ file_exists(storage_path('app/public/'.$course->banner_path)) ? filemtime(storage_path('app/public/'.$course->banner_path)) : time() }}"
                                                 class="card-img-top"
                                                 alt="{{ $course->title }}"
                                                 style="height: 140px; object-fit: cover;">
                                        @endif

                                        <div class="card-body d-flex flex-column p-4">
                                            <h5 class="card-title fw-bold text-dark mb-3">{{ $course->title }}</h5>

                                            @if(!empty(optional($course->specialty)->specialty))
                                                <p class="text-muted small mb-2">
                                                    <i class="bi bi-tag-fill me-1"></i>{{ $course->specialty->specialty }}
                                                </p>
                                            @endif

                                            @if(!empty($courseTraining?->modality))
                                                <p class="text-muted small mb-2">
                                                    <i class="bi bi-laptop me-1"></i>{{ $courseTraining->modality }}
                                                </p>
                                            @endif

                                            @if(!empty($course->reference_price))
                                                <p class="text-muted small mb-3">
                                                    <i class="bi bi-cash-stack me-1"></i>{{ $course->reference_price }}
                                                </p>
                                            @endif

                                            @if($courseTraining)
                                                <a href="{{ route('student.courses.show', $courseTraining->training_id) }}" class="btn btn-primary btn-sm mt-auto">
                                                    Ver detalles
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-primary btn-sm mt-auto" disabled>
                                                    Ver detalles
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">No hay cursos disponibles por ahora.</div>
                    @endif
                </div>
            </div>
        @endif

    </div>

    <style>
        .card { transition: box-shadow 0.3s ease, transform 0.3s ease; }
        .card:hover { box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.12) !important; transform: translateY(-4px); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        .course-banner-image { background-position: center center; background-size: cover; width: 100%; }
        .course-banner-fallback { background: #0d6efd; }
        .course-banner-overlay { position: absolute; inset: 0; pointer-events: none; }
        .course-card-clickable { cursor: pointer; }
        .course-card-clickable .card-body { pointer-events: none; }
        .course-card-clickable button { pointer-events: auto; }
        .course-card-clickable a { pointer-events: auto; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.course-card-clickable').forEach(function (card) {
                card.addEventListener('click', function (event) {
                    if (event.target.closest('a, button')) {
                        return;
                    }
                    var url = card.getAttribute('data-url');
                    if (url) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>

@endsection