@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <!-- Header -->
        <div class="mb-5">
            <h1 class="h3 mb-4 text-gray-800">Mis Capacitaciones</h1>
            <p class="text-muted">Accede a tus cursos y gestiona tus estudiantes</p>
        </div>

        <!-- Courses Grid -->
        @if($trainings->count() > 0)
            <div class="row g-4">
                @foreach($trainings as $training)
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm rounded-3 border-0 position-relative overflow-hidden transition-all"
                                style="transition: box-shadow 0.3s, transform 0.3s;">

                                <!-- Gradient Header -->
                                <div class="bg-primary bg-gradient p-5 text-white d-flex align-items-center justify-content-center"
                                    style="height: 140px; font-size: 3rem; font-weight: bold; opacity: 0.9;">
                                    {{ strtoupper(substr($training->course->title, 0, 1)) }}
                                </div>

                                <!-- Card Body -->
                                <div class="card-body d-flex flex-column">
                                    <!-- Course Info -->
                                    <div>
                                        <h5 class="card-title fw-bold text-dark mb-2">
                                            {{ $training->course->title }}
                                        </h5>
                                        <p class="text-muted small">
                                            Código: <strong class="text-dark">{{ $training->course->code ?? 'N/A' }}</strong>
                                        </p>
                                    </div>

                                    <!-- Stats -->
                                    <div class="row text-center mt-4 pt-3 border-top g-2 flex-grow-1 align-items-end">
                                        <div class="col-6">
                                            <div class="h5 fw-bold text-primary mb-1">
                                                {{ $training->enrollments->count() }}
                                            </div>
                                            <small class="text-muted">Alumnos</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="h5 fw-bold text-success mb-1">
                                                {{ ucfirst($training->modality) }}
                                            </div>
                                            <small class="text-muted">Modalidad</small>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="mt-3">
                                        <span class="badge bg-success">
                                            ✓ Activo
                                        </span>
                                    </div>
                                </div>

                                <!-- Footer Arrow -->
                                <div class="card-footer bg-light border-top p-3 text-end">
                                    <i class="bi bi-arrow-right text-muted" style="font-size: 1.2rem;"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem;" class="text-muted"></i>
                    <h5 class="card-title mt-4 text-dark fw-bold">No hay capacitaciones</h5>
                    <p class="text-muted">
                        No tienes cursos asignados aún. Contacta con el administrador.
                    </p>
                </div>
            </div>
        @endif
    </div>

    <style>
        .card {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-4px);
        }

        a {
            color: inherit;
            text-decoration: none !important;
        }

        a:hover {
            color: inherit;
        }
    </style>
@endsection