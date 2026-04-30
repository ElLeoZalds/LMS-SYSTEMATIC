@extends('layouts.app')

@section('title', 'Calendario')

@section('content')

<div class="container-fluid mt-4">
    <div class="row gy-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                <div>
                    <h2 class="mb-2">Calendario</h2>
                    <p class="text-muted mb-0">Aquí se muestran los horarios de clases y fechas de entrega para estudiantes y docentes.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('dashboard.calendar', ['view' => 'week', 'date' => $selectedDate->toDateString()]) }}"
                        class="btn {{ $viewMode === 'week' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">Vista semanal</a>
                    <a href="{{ route('dashboard.calendar', ['view' => 'month', 'date' => $selectedDate->toDateString()]) }}"
                        class="btn {{ $viewMode === 'month' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm">Vista mensual</a>
                    <a href="#" class="btn btn-success btn-sm">Agregar horario</a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $viewMode === 'month' ? 'Mes actual' : 'Semana actual' }}
                            </h5>
                            <p class="text-muted mb-0">
                                {{ $viewMode === 'month' ? $selectedDate->format('F Y') : $weekStart->format('d M') . ' - ' . $weekEnd->format('d M') }}
                            </p>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('dashboard.calendar', ['view' => $viewMode, 'date' => $prevDate]) }}"
                                class="btn btn-outline-secondary">Anterior</a>
                            <a href="{{ route('dashboard.calendar', ['view' => $viewMode, 'date' => $nextDate]) }}"
                                class="btn btn-outline-secondary">Siguiente</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center mb-0 calendar-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-2">Lunes</th>
                                    <th class="py-2">Martes</th>
                                    <th class="py-2">Miércoles</th>
                                    <th class="py-2">Jueves</th>
                                    <th class="py-2">Viernes</th>
                                    <th class="py-2">Sábado</th>
                                    <th class="py-2">Domingo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($calendarWeeks as $week)
                                <tr>
                                    @foreach ($week as $day)
                                    <td
                                        class="calendar-cell p-2 {{ $viewMode === 'month' && !$day['currentMonth'] ? 'calendar-day-muted bg-light' : '' }}">
                                        <div class="calendar-cell-number">{{ $day['date']->format('d') }}</div>
                                        @if (count($day['events']) === 0)
                                        <div class="calendar-cell-empty text-muted small mt-5">Sin actividades</div>
                                        @else
                                        @foreach ($day['events'] as $event)
                                        <div
                                            class="calendar-event {{ $event->modalidad === 'Virtual' ? 'bg-info bg-opacity-10 border border-info text-dark' : 'bg-primary bg-opacity-10 border border-primary text-dark' }}">
                                            <div class="event-title">{{ $event->curso }}</div>
                                            <div class="event-meta">{{ $event->inicio ? $event->inicio : '' }} ·
                                                {{ $event->modalidad }}
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h6 class="mb-3">Leyenda</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary">Clase</span>
                            <span class="badge bg-info">Virtual</span>
                            <span class="badge bg-secondary">Presencial</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Resumen rápido</h5>
                    <div class="row text-center mt-3">
                        <div class="col-6 mb-3">
                            <div class="bg-light rounded-3 p-3">
                                <h3 class="mb-0">{{ $classesThisWeek }}</h3>
                                <small class="text-muted">Clases esta semana</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-light rounded-3 p-3">
                                <h3 class="mb-0">{{ $tasksPending }}</h3>
                                <small class="text-muted">Tareas pendientes</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="mb-3">Para docentes</h6>
                        <p class="small text-muted mb-2">Gestiona horarios, agrega clases nuevas y asigna tareas desde el panel.</p>
                        <a href="#" class="btn btn-outline-primary btn-sm">Gestionar horario</a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Tareas próximas</h5>
                        <span class="badge bg-secondary">{{ $upcomingTasks->count() }}</span>
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse ($upcomingTasks as $task)
                        <li class="list-group-item px-0 border-0 pb-3">
                            <strong>{{ $task->titulo }}</strong>
                            <div class="small text-muted">Curso: {{ $task->curso }}</div>
                            <div class="small text-muted">Fecha límite: {{ $task->fechafin }}</div>
                        </li>
                        @empty
                        <li class="list-group-item px-0 border-0 pb-3 text-muted">
                            No hay tareas próximas.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection