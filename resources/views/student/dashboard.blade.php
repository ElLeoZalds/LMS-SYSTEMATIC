@extends('layouts.app')

@section('content')

    <div class="container-fluid px-4 py-4">

        <h1 class="h3 mb-4 text-gray-800"><i class="bi bi-mortarboard-fill me-2"></i>Mi Dashboard</h1>

        <!-- STATS SECTION -->
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

        <!-- COURSES SECTION -->
        <div class="mb-3">
            <h3 class="text-gray-800 fw-bold">
                <i class="bi bi-journal-text me-2"></i>Mis Cursos
            </h3>
        </div>

        @if($enrollments->count() > 0)
            <div class="row g-4">

                @foreach($enrollments as $enrollment)

                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card shadow mb-0 h-100 border-0">
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-gray-800 mb-2">{{ $enrollment->training->course->title }}</h5>

                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">
                                        <i
                                            class="bi bi-person-fill me-1"></i>{{ $enrollment->training->teacher->person->first_names ?? 'Sin profesor' }}
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-tag-fill me-1"></i>{{ ucfirst($enrollment->training->modality) }}
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
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

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge @if($enrollment->status == 'A') bg-success @else bg-secondary @endif">
                                        {{ $enrollment->status == 'A' ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    <a href="{{ route('student.courses.show', $enrollment->training->training_id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="bi bi-arrow-right me-1"></i>Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

            </div>
        @else
            <div class="alert alert-info text-center" role="alert">
                <i class="bi bi-inbox me-2"></i>No tienes cursos inscritos aún.
            </div>
        @endif

    </div>

@endsection