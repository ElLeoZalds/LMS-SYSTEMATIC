@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <div class="container-fluid px-4 py-1">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800 fw-bold">Hola, {{ $studentName ?: 'estudiante' }}</h1>
                <p class="text-muted mb-0">{{ now()->translatedFormat('d \de F \de Y') }}</p>
            </div>
            <div class="text-lg-end">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                    <i class="bi bi-mortarboard-fill me-2"></i>Tienes {{ $stats['active_trainings'] ?? 0 }} capacitaciones activas
                </span>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 fw-bold text-dark mb-0">
                                <i class="bi bi-bell-fill me-2 text-warning"></i>Notificaciones
                            </h2>
                            <a href="{{ route('student.notifications') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Ver todas las notificaciones
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($notifications->isEmpty())
                            <div class="text-muted">No tienes notificaciones nuevas</div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($notifications as $notification)
                                    <a href="{{ $notification['url'] ?? route('student.dashboard') }}" class="list-group-item list-group-item-action px-0 py-3 d-flex align-items-start gap-3">
                                        <div class="rounded-circle bg-light p-2 mt-1">
                                            <i class="bi {{ $notification['icon'] ?? 'bi-bell' }} text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start gap-2">
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $notification['title'] }}</div>
                                                    <div class="small text-muted">{{ $notification['message'] }}</div>
                                                </div>
                                                <span class="badge bg-light text-dark border">{{ $notification['badge'] ?? 'Nuevo' }}</span>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                {{ $notification['created_at'] instanceof \Carbon\Carbon ? $notification['created_at']->diffForHumans() : '' }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h2 class="h5 fw-bold text-dark mb-0">
                            <i class="bi bi-calendar2-week me-2 text-danger"></i>Próximos Vencimientos
                        </h2>
                    </div>
                    <div class="card-body">
                        @if($upcomingDeadlines->isEmpty())
                            <div class="text-muted">No tienes actividades pendientes</div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($upcomingDeadlines as $deadline)
                                    @php
                                        $daysUntil = now()->startOfDay()->diffInDays(Carbon::parse($deadline['sort_date']), false);
                                        $deadlineClass = $deadline['state'] === 'Vencida' || $daysUntil <= 0 ? 'text-danger' : ($daysUntil <= 2 ? 'text-warning' : 'text-success');
                                    @endphp
                                    <div class="list-group-item px-0 py-3">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $deadline['title'] }}</div>
                                                <div class="small text-muted">{{ $deadline['training_title'] }}</div>
                                                <div class="small text-muted mt-1">{{ $deadline['deadline'] }}</div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge {{ $deadline['state'] === 'Vencida' ? 'bg-danger' : ($deadline['state'] === 'Pendiente' ? 'bg-warning text-dark' : 'bg-success') }}">{{ $deadline['type'] }}</span>
                                                <div class="small mt-2 {{ $deadlineClass }}">{{ $deadline['state'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h2 class="h5 fw-bold text-dark mb-0">
                            <i class="bi bi-speedometer2 me-2 text-primary"></i>Mi Progreso
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $overallProgress }}%" aria-valuenow="{{ $overallProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mb-4">Has completado el {{ $overallProgress }}% de tus capacitaciones activas</p>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <div class="small text-muted">Capacitaciones activas</div>
                                    <div class="fw-bold text-dark">{{ $stats['active_trainings'] ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <div class="small text-muted">Tareas completadas</div>
                                    <div class="fw-bold text-dark">{{ $stats['completed_tasks'] ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <div class="small text-muted">Evaluaciones aprobadas</div>
                                    <div class="fw-bold text-dark">{{ $stats['approved_assessments'] ?? 0 }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <div class="small text-muted">Promedio general</div>
                                    <div class="fw-bold text-dark">{{ number_format($stats['average_grade'] ?? 0, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection