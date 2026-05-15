@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="mb-4">
            <h1 class="h3 mb-4 text-gray-800">Resumen de Actividad</h1>
            <p class="text-muted small">Vista general de tus capacitaciones y tareas recientes.</p>
        </div>

        {{-- KPIs de rendimiento docente y Estadísticas unificadas en un Grid Responsivo --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-md-4 col-xl-2.4 col-xxl-2.4" style="flex: 1 0 20%; min-width: 200px;">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-bar-chart-fill text-info h4 mb-2"></i>
                        <h5 class="card-title h6 fw-bold mb-1">{{ number_format($averageScore, 2, ',', '.') }}</h5>
                        <p class="card-text small text-muted mb-0">Promedio de notas en exámenes</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-xl-2.4 col-xxl-2.4" style="flex: 1 0 20%; min-width: 200px;">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-check text-success h4 mb-2"></i>
                        <h5 class="card-title h6 fw-bold mb-1">{{ $totalAttempts }}</h5>
                        <p class="card-text small text-muted mb-0">Total de intentos de examen</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-xl-2.4 col-xxl-2.4" style="flex: 1 0 20%; min-width: 200px;">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-primary h4 mb-2"></i>
                        <h5 class="card-title h6 fw-bold mb-1">{{ $totalStudents }}</h5>
                        <p class="card-text small text-muted mb-0">Total de Alumnos</p>
                    </div> {{-- <-- ¡Este era el div que faltaba cerrar! --}}
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-xl-2.4 col-xxl-2.4" style="flex: 1 0 20%; min-width: 200px;">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-mortarboard-fill text-success h4 mb-2"></i>
                        <h5 class="card-title h6 fw-bold mb-1">{{ $totalActiveTrainings }}</h5>
                        <p class="card-text small text-muted mb-0">Capacitaciones Activas</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-xl-2.4 col-xxl-2.4" style="flex: 1 0 20%; min-width: 200px;">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-clipboard-check-fill text-warning h4 mb-2"></i>
                        <h5 class="card-title h6 fw-bold mb-1">{{ $totalTasks }}</h5>
                        <p class="card-text small text-muted mb-0">Tareas Creadas</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección de Últimos Trabajos --}}
        <div class="bg-white rounded-lg shadow-md p-4 mt-4">
            <h2 class="h5 font-weight-bold mb-4">Actividad Reciente</h2>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small fw-bold">Tarea</th>
                            <th class="small fw-bold">Curso</th>
                            <th class="small fw-bold">Fecha de Creación</th>
                            <th class="small fw-bold">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $activity)
                            <tr>
                                <td class="small">{{ $activity->title }}</td>
                                <td class="small">{{ $activity->training->course->title ?? 'N/A' }}</td>
                                <td class="small">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge {{ $activity->active ? 'bg-success' : 'bg-secondary' }} small">
                                        {{ $activity->active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted small py-3">No hay actividades recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection