@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">{{ $training->course->title }}</h1>
                <small class="text-muted">Código: {{ $training->course->code ?? 'N/A' }} | Modalidad:
                    <strong>{{ ucfirst($training->modality) }}</strong></small>
            </div>
            <a href="{{ route('teacher.courses') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver a Cursos
            </a>
        </div>

        <!-- Statistics Row -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-primary h4 mb-2"></i>
                        <h5 class="h6 fw-bold">{{ $totalStudents }}</h5>
                        <small class="text-muted">Estudiantes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle-fill text-success h4 mb-2"></i>
                        <h5 class="h6 fw-bold">{{ $totalAssessments }}</h5>
                        <small class="text-muted">Tareas/Evaluaciones</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check text-info h4 mb-2"></i>
                        <h5 class="h6 fw-bold">{{ $totalAttendanceRecords }}</h5>
                        <small class="text-muted">Registros de Asistencia</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <span class="badge bg-success p-2 mb-2">Activo</span>
                        <small class="text-muted d-block">Estado del Curso</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card with Tabs -->
        <div class="card shadow mb-4">
            <!-- Nav Tabs -->
            <div class="card-header bg-white border-bottom">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=inicio"
                            class="nav-link @if(request('tab', 'inicio') === 'inicio') active @endif" id="inicio-tab"
                            role="tab">
                            <i class="bi bi-house-fill me-2"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=estudiantes"
                            class="nav-link @if(request('tab') === 'estudiantes') active @endif" id="estudiantes-tab"
                            role="tab">
                            <i class="bi bi-people-fill me-2"></i>Estudiantes
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=asistencias"
                            class="nav-link @if(request('tab') === 'asistencias') active @endif" id="asistencias-tab"
                            role="tab">
                            <i class="bi bi-clipboard-check me-2"></i>Asistencias
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=contenido"
                            class="nav-link @if(request('tab') === 'contenido') active @endif" id="contenido-tab"
                            role="tab">
                            <i class="bi bi-book-fill me-2"></i>Contenido/Tareas
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=calificaciones"
                            class="nav-link @if(request('tab') === 'calificaciones') active @endif" id="calificaciones-tab"
                            role="tab">
                            <i class="bi bi-check-circle-fill me-2"></i>Calificaciones
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="card-body">

                <!-- Inicio / Dashboard -->
                @if(request('tab', 'inicio') === 'inicio')
                    <!-- Quick Actions -->
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <a href="{{ route('teacher.attendance', $training->training_id) }}" class="text-decoration-none">
                                <div class="card border-start border-primary border-3 shadow-sm h-100"
                                    style="cursor: pointer; transition: box-shadow 0.3s;">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold text-dark mb-2">
                                            <i class="bi bi-calendar-check text-primary me-2"></i>Registrar Asistencia
                                        </h5>
                                        <p class="card-text text-muted small">Marca la asistencia de tus estudiantes</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('teacher.tasks.create', $training->training_id) }}" class="text-decoration-none">
                                <div class="card border-start border-success border-3 shadow-sm h-100"
                                    style="cursor: pointer; transition: box-shadow 0.3s;">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold text-dark mb-2">
                                            <i class="bi bi-plus-circle text-success me-2"></i>Crear Tarea
                                        </h5>
                                        <p class="card-text text-muted small">Asigna una nueva tarea o evaluación</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('teacher.students', $training->training_id) }}" class="text-decoration-none">
                                <div class="card border-start border-info border-3 shadow-sm h-100"
                                    style="cursor: pointer; transition: box-shadow 0.3s;">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold text-dark mb-2">
                                            <i class="bi bi-people text-info me-2"></i>Ver Estudiantes
                                        </h5>
                                        <p class="card-text text-muted small">Consulta la lista completa de estudiantes</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="card border-start border-warning border-3 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold text-dark mb-3">
                                        <i class="bi bi-info-circle text-warning me-2"></i>Información
                                    </h5>
                                    <div class="small">
                                        <p class="mb-2"><strong>Modalidad:</strong> {{ ucfirst($training->modality) }}</p>
                                        <p class="mb-2"><strong>Estado:</strong> <span class="badge bg-success">Activo</span>
                                        </p>
                                        <p class="mb-0"><strong>Estudiantes:</strong> {{ $totalStudents }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estudiantes -->
                @elseif(request('tab') === 'estudiantes')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Estudiantes Matriculados <span
                                class="badge bg-primary">{{ $totalStudents }}</span></h5>
                    </div>

                    @if($training->enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($training->enrollments as $enrollment)
                                        <tr>
                                            <td class="fw-bold">{{ $enrollment->student->person->first_names }}
                                                {{ $enrollment->student->person->last_names }}</td>
                                            <td>{{ $enrollment->student->person->document_number }}</td>
                                            <td><small>{{ $enrollment->student->person->email }}</small></td>
                                            <td><small>{{ $enrollment->student->person->phone }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center mb-0" role="alert">
                            <i class="bi bi-info-circle me-2"></i>No hay estudiantes matriculados en este curso aún.
                        </div>
                    @endif

                    <!-- Asistencias -->
                @elseif(request('tab') === 'asistencias')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Registro de Asistencias</h5>
                        <a href="{{ route('teacher.attendance', $training->training_id) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-arrow-right me-1"></i>Registrar Asistencia
                        </a>
                    </div>
                    <p class="text-muted">Total de registros: <strong>{{ $totalAttendanceRecords }}</strong></p>

                    <!-- Contenido/Tareas -->
                @elseif(request('tab') === 'contenido')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Contenido y Tareas</h5>
                        <a href="{{ route('teacher.tasks.create', $training->training_id) }}" class="btn btn-sm btn-success">
                            <i class="bi bi-plus-lg me-1"></i>Nueva Tarea
                        </a>
                    </div>

                    @if($training->assessments->count() > 0)
                        <div class="row g-3">
                            @foreach($training->assessments as $assessment)
                                <div class="col-12">
                                    <div class="card border-start border-primary border-3 shadow-sm mb-0">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="card-title fw-bold text-dark mb-2">{{ $assessment->title }}</h6>
                                                    <p class="card-text text-muted small mb-0">{{ $assessment->description }}</p>
                                                </div>
                                                <span class="badge @if($assessment->active) bg-success @else bg-secondary @endif ms-2">
                                                    {{ $assessment->active ? 'Activa' : 'Inactiva' }}
                                                </span>
                                            </div>
                                            <small class="text-muted d-block mt-2">
                                                <i class="bi bi-calendar-event me-1"></i>Vencimiento:
                                                <strong>{{ $assessment->end_date->format('d/m/Y') }}</strong>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center mb-0" role="alert">
                            <i class="bi bi-inbox me-2"></i>No hay tareas creadas aún.
                        </div>
                    @endif

                    <!-- Calificaciones -->
                @elseif(request('tab') === 'calificaciones')
                    <h5 class="fw-bold text-dark mb-3">Calificaciones</h5>

                    <div class="alert alert-warning text-center mb-0" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>El módulo de calificaciones estará disponible pronto.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection