@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Panel del docente</h1>
                <p class="text-muted mb-0">Tus capacitaciones activas, pendientes y recursos clave en un solo lugar.</p>
            </div>
            <a href="{{ route('teacher.courses') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-collection me-1"></i>Ver todas las capacitaciones
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Estudiantes</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Capacitaciones activas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalActiveTrainings }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-book-half fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Tareas registradas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTasks }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-list-task fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Promedio general</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $averageScore }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-bar-chart-fill fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">Mis capacitaciones activas</h5>
                                <small class="text-muted">Gestiona cada curso desde una vista rápida y enfocada.</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($activeTrainings->isEmpty())
                            <div class="alert alert-secondary mb-0">No tienes capacitaciones activas en este momento.</div>
                        @else
                            <div class="row g-3">
                                @foreach($activeTrainings as $training)
                                    <div class="col-12">
                                        <div class="border rounded p-3 h-100">
                                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ optional($training->course)->title ?? 'Sin curso' }}</h6>
                                                    <small class="text-muted">{{ optional($training->course->specialty)->specialty ?? 'Sin especialidad' }}</small>
                                                </div>
                                                <span class="badge bg-success">Activo</span>
                                            </div>
                                            <div class="small text-muted mb-3">
                                                <div><i class="bi bi-clock me-1"></i>{{ $training->schedules->isNotEmpty() ? $training->schedules->map(fn($schedule) => $schedule->date->format('d/m/Y') . ' ' . $schedule->start_time)->join(', ') : 'Sin horario asignado' }}</div>
                                                <div class="mt-1"><i class="bi bi-people me-1"></i>{{ $training->enrollments->count() }} estudiantes matriculados</div>
                                            </div>
                                            <a href="{{ route('teacher.courses.show', $training->training_id) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-box-arrow-up-right me-1"></i>Gestionar curso
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-dark">Pendientes de hoy</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">Tareas por revisar</h6>
                                <span class="badge bg-warning text-dark">{{ $pendingTasks->count() }}</span>
                            </div>
                            @if($pendingTasks->isEmpty())
                                <div class="text-muted small">No hay tareas pendientes en los próximos 7 días.</div>
                            @else
                                <ul class="list-unstyled mb-0">
                                    @foreach($pendingTasks->take(5) as $task)
                                        <li class="border rounded p-2 mb-2 small">
                                            <div class="fw-semibold">{{ $task->title }}</div>
                                            <div class="text-muted">{{ optional($task->training->course)->title ?? 'Sin curso' }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">Evaluaciones próximas</h6>
                                <span class="badge bg-info text-dark">{{ $upcomingAssessments->count() }}</span>
                            </div>
                            @if($upcomingAssessments->isEmpty())
                                <div class="text-muted small">No hay evaluaciones próximas en los próximos 7 días.</div>
                            @else
                                <ul class="list-unstyled mb-0">
                                    @foreach($upcomingAssessments->take(5) as $assessment)
                                        <li class="border rounded p-2 mb-2 small">
                                            <div class="fw-semibold">{{ $assessment->title }}</div>
                                            <div class="text-muted">{{ optional($assessment->training->course)->title ?? 'Sin curso' }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection