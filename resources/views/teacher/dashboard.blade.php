@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="mb-4">
            <h1 class="h3 mb-4 text-gray-800">Resumen de Actividad</h1>
            <p class="text-muted small">Vista general de tus capacitaciones y tareas recientes.</p>
        </div>

        {{-- Sección de Estadísticas --}}
        <div class="row g-3 mb-5"> {{-- Gap reducido --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3"> {{-- Padding reducido --}}
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-primary h4 mb-2"></i>
                        <h5 class="card-title h6 fw-bold">{{ $totalStudents }}</h5>
                        <p class="card-text small text-muted">Total de Alumnos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3">
                    <div class="card-body text-center">
                        <i class="bi bi-mortarboard-fill text-success h4 mb-2"></i>
                        <h5 class="card-title h6 fw-bold">{{ $totalActiveTrainings }}</h5>
                        <p class="card-text small text-muted">Capacitaciones Activas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3">
                    <div class="card-body text-center">
                        <i class="bi bi-clipboard-check-fill text-warning h4 mb-2"></i>
                        <h5 class="card-title h6 fw-bold">{{ $totalTasks }}</h5>
                        <p class="card-text small text-muted">Tareas Creadas</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sección de Últimos Trabajos --}}
        <div class="bg-white rounded-lg shadow-md p-4">
            <h2 class="h5 font-weight-bold mb-4">Actividad Reciente</h2>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0"> {{-- Alta densidad --}}
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