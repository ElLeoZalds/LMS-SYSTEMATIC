@extends('layouts.app')

@section('noSidebar', true)

@section('content')
<div class="container-fluid">
    <div class="row g-0 min-vh-100">
        
        <!-- Sidebar Navigation -->
        <aside class="col-lg-3 col-xl-2 bg-white border-end" style="min-height: 100vh;">
            <div class="sticky-top" style="top: 70px;">
                <!-- Header -->
                <div class="p-4 border-bottom">
                    <a href="{{ route('teacher.courses') }}" class="btn btn-sm btn-link text-muted text-decoration-none mb-3">
                        <i class="bi bi-arrow-left me-2"></i>
                        <span>Volver a Cursos</span>
                    </a>
                    <h5 class="fw-bold text-dark mt-3 mb-1">{{ $training->course->title }}</h5>
                    <small class="text-muted">Código: {{ $training->course->code ?? 'N/A' }}</small>
                </div>

                <!-- Navigation Menu -->
                <nav class="nav flex-column p-3">
                    <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=inicio" 
                       class="nav-link text-dark @if(request('tab', 'inicio') === 'inicio') bg-light border-start border-primary border-3 text-primary @endif">
                        <i class="bi bi-house-fill me-2"></i>
                        <span>Inicio</span>
                    </a>

                    <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=estudiantes" 
                       class="nav-link text-dark @if(request('tab') === 'estudiantes') bg-light border-start border-primary border-3 text-primary @endif">
                        <i class="bi bi-people-fill me-2"></i>
                        <span>Estudiantes</span>
                    </a>

                    <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=asistencias" 
                       class="nav-link text-dark @if(request('tab') === 'asistencias') bg-light border-start border-primary border-3 text-primary @endif">
                        <i class="bi bi-clipboard-check me-2"></i>
                        <span>Asistencias</span>
                    </a>

                    <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=contenido" 
                       class="nav-link text-dark @if(request('tab') === 'contenido') bg-light border-start border-primary border-3 text-primary @endif">
                        <i class="bi bi-book-fill me-2"></i>
                        <span>Contenido/Tareas</span>
                    </a>

                    <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=calificaciones" 
                       class="nav-link text-dark @if(request('tab') === 'calificaciones') bg-light border-start border-primary border-3 text-primary @endif">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <span>Calificaciones</span>
                    </a>
                </nav>

                <!-- Quick Stats -->
                <div class="p-3 m-3 bg-light rounded-3 border">
                    <h6 class="text-uppercase text-muted small fw-bold mb-3">Estadísticas</h6>
                    <div class="mb-3">
                        <h5 class="text-primary fw-bold mb-1">{{ $totalStudents }}</h5>
                        <small class="text-muted">Estudiantes</small>
                    </div>
                    <div class="mb-3">
                        <h5 class="text-success fw-bold mb-1">{{ $totalAssessments }}</h5>
                        <small class="text-muted">Tareas/Eval.</small>
                    </div>
                    <div>
                        <h5 class="text-info fw-bold mb-1">{{ $totalAttendanceRecords }}</h5>
                        <small class="text-muted">Asistencias</small>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="col-lg-9 col-xl-10 px-4 py-5">
            
            <!-- Inicio / Dashboard -->
            @if(request('tab', 'inicio') === 'inicio')
                <h2 class="h3 fw-bold text-dark mb-4">Panel del Curso</h2>
                
                <!-- Quick Actions -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <a href="{{ route('teacher.attendance', $training->training_id) }}" class="text-decoration-none">
                            <div class="card shadow-sm border-0 border-start border-primary border-3 p-4 h-100 hover-shadow" style="transition: box-shadow 0.3s;">
                                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-calendar-check text-primary me-2"></i>Registrar Asistencia</h5>
                                <small class="text-muted">Marca la asistencia de tus estudiantes</small>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <a href="{{ route('teacher.tasks.create', $training->training_id) }}" class="text-decoration-none">
                            <div class="card shadow-sm border-0 border-start border-success border-3 p-4 h-100 hover-shadow" style="transition: box-shadow 0.3s;">
                                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-plus-circle text-success me-2"></i>Crear Tarea</h5>
                                <small class="text-muted">Asigna una nueva tarea o evaluación</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6">
                        <a href="{{ route('teacher.students', $training->training_id) }}" class="text-decoration-none">
                            <div class="card shadow-sm border-0 border-start border-info border-3 p-4 h-100 hover-shadow" style="transition: box-shadow 0.3s;">
                                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-people text-info me-2"></i>Ver Estudiantes</h5>
                                <small class="text-muted">Consulta la lista completa de estudiantes</small>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card shadow-sm border-0 border-start border-warning border-3 p-4 h-100">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-warning me-2"></i>Información</h5>
                            <div class="small">
                                <p class="mb-2"><strong>Modalidad:</strong> {{ ucfirst($training->modality) }}</p>
                                <p class="mb-2"><strong>Estado:</strong> <span class="badge bg-success">Activo</span></p>
                                <p class="mb-0"><strong>Estudiantes:</strong> {{ $totalStudents }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Actividad Reciente</h5>
                        <p class="text-muted small">La información de actividad reciente se mostrará aquí.</p>
                    </div>
                </div>

            <!-- Estudiantes -->
            @elseif(request('tab') === 'estudiantes')
                <h2 class="h3 fw-bold text-dark mb-4">Estudiantes Matriculados <span class="badge bg-primary">{{ $totalStudents }}</span></h2>
                
                @if($training->enrollments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover border align-middle">
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
                                        <td class="fw-bold">{{ $enrollment->student->person->first_names }} {{ $enrollment->student->person->last_names }}</td>
                                        <td>{{ $enrollment->student->person->document_number }}</td>
                                        <td><small>{{ $enrollment->student->person->email }}</small></td>
                                        <td><small>{{ $enrollment->student->person->phone }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center" role="alert">
                        <i class="bi bi-info-circle me-2"></i>No hay estudiantes matriculados en este curso aún.
                    </div>
                @endif

            <!-- Asistencias -->
            @elseif(request('tab') === 'asistencias')
                <h2 class="h3 fw-bold text-dark mb-4">Registro de Asistencias</h2>
                
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <a href="{{ route('teacher.attendance', $training->training_id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-right me-2"></i>Ir a Registro de Asistencias
                        </a>
                        <p class="text-muted mt-3 mb-0">Total de registros: <strong>{{ $totalAttendanceRecords }}</strong></p>
                    </div>
                </div>

            <!-- Contenido/Tareas -->
            @elseif(request('tab') === 'contenido')
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h3 fw-bold text-dark">Contenido y Tareas</h2>
                    <a href="{{ route('teacher.tasks.create', $training->training_id) }}" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-lg me-2"></i>Nueva Tarea
                    </a>
                </div>
                
                @if($training->assessments->count() > 0)
                    <div class="row g-3">
                        @foreach($training->assessments as $assessment)
                            <div class="col-12">
                                <div class="card shadow-sm border-0">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold mb-2">{{ $assessment->title }}</h5>
                                        <p class="card-text text-muted small">{{ $assessment->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <small class="text-muted">Vencimiento: <strong>{{ $assessment->end_date->format('d/m/Y') }}</strong></small>
                                            <span class="badge @if($assessment->active) bg-success @else bg-secondary @endif">
                                                {{ $assessment->active ? 'Activa' : 'Inactiva' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info text-center" role="alert">
                        <i class="bi bi-inbox me-2"></i>No hay tareas creadas aún.
                    </div>
                @endif

            <!-- Calificaciones -->
            @elseif(request('tab') === 'calificaciones')
                <h2 class="h3 fw-bold text-dark mb-4">Calificaciones</h2>
                
                <div class="alert alert-warning text-center" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>El módulo de calificaciones estará disponible pronto.
                </div>
            @endif
        </main>
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    a {
        color: inherit;
        text-decoration: none !important;
    }
</style>
@endsection
