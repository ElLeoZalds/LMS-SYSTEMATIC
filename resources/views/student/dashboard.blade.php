@extends('layouts.app')

@section('content')

    <div class="container-fluid px-4 py-1">

        <h1 class="h3 mb-4 text-gray-800 fw-bold"><i class="bi bi-mortarboard-fill me-2"></i>Mi Dashboard</h1>

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

        @if($enrollments->count() > 0)
            <div class="row g-4">

                @foreach($enrollments as $enrollment)

                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm rounded-3 border-0 position-relative overflow-hidden transition-all">
                            
                            <div class="bg-primary bg-gradient p-4 text-white d-flex align-items-center justify-content-center"
                                style="height: 120px; font-size: 2.5rem; font-weight: bold; opacity: 0.9;">
                                {{ strtoupper(substr($enrollment->training->course->title, 0, 1)) }}
                            </div>

                            <div class="card-body d-flex flex-column p-4">
                                <div class="mb-3">
                                    <h5 class="card-title fw-bold text-dark mb-2 line-clamp-2" style="font-size: 1.1rem;">
                                        {{ $enrollment->training->course->title }}
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
                                    <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.75rem;">
                                        <small class="text-muted">Progreso</small>
                                        <small class="fw-bold text-primary">
                                            @php
                                                $progress = $enrollment->status == 'A' ? 50 : 10;
                                            @endphp
                                            {{ $progress }}%
                                        </small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%"
                                            aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge @if($enrollment->status == 'A') bg-success bg-opacity-10 text-success border border-success border-opacity-25 @else bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 @endif px-2 py-1" style="font-size: 0.75rem;">
                                        {{ $enrollment->status == 'A' ? '✓ Activo' : 'Inactivo' }}
                                    </span>
                                    <a href="{{ route('student.courses.show', $enrollment->training->training_id) }}"
                                        class="btn btn-sm btn-primary px-3">
                                        Ver<i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        @else
            <div class="alert alert-info text-center border-0 shadow-sm" role="alert">
                <i class="bi bi-inbox me-2"></i>No tienes cursos inscritos aún.
            </div>
        @endif

    </div>

    <style>
        .card { transition: box-shadow 0.3s ease, transform 0.3s ease; }
        .card:hover { box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.12) !important; transform: translateY(-4px); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>

@endsection