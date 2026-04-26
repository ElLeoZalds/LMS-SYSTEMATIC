@extends('layouts.app')

@section('title', 'Calendar')

@section('content')
    <style>
        .calendar-table {
            table-layout: fixed;
            width: 100%;
        }

        .calendar-table th,
        .calendar-table td {
            min-width: 120px;
            min-height: 180px;
            vertical-align: top;
            padding: 0.75rem;
        }

        .calendar-cell {
            position: relative;
            height: 100%;
        }

        .calendar-cell-number {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .calendar-cell-empty {
            min-height: 160px;
        }

        .calendar-event {
            display: block;
            margin-bottom: 0.6rem;
            text-align: left;
            padding: 0.8rem;
            border-radius: 0.8rem;
        }

        .calendar-event .event-title {
            font-size: 0.95rem;
            font-weight: 700;
        }

        .calendar-event .event-meta {
            font-size: 0.82rem;
        }

        .calendar-day-muted {
            opacity: 0.65;
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row gy-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                    <div>
                        <h2 class="mb-2">Calendar</h2>
                        <p class="text-muted mb-0">Class schedules and task deadlines are displayed here for both students and teachers.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('dashboard.calendar', ['view' => 'week', 'date' => $selectedDate->toDateString()]) }}"
                            class="btn {{ $viewMode === 'week' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">Week
                            view</a>
                        <a href="{{ route('dashboard.calendar', ['view' => 'month', 'date' => $selectedDate->toDateString()]) }}"
                            class="btn {{ $viewMode === 'month' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm">Month
                            view</a>
                        <a href="#" class="btn btn-success btn-sm">Add schedule</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div
                            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $viewMode === 'month' ? 'Current month' : 'Current week' }}
                                </h5>
                                <p class="text-muted mb-0">
                                    {{ $viewMode === 'month' ? $selectedDate->format('F Y') : $weekStart->format('d M') . ' - ' . $weekEnd->format('d M') }}
                                </p>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('dashboard.calendar', ['view' => $viewMode, 'date' => $prevDate]) }}"
                                    class="btn btn-outline-secondary">Previous</a>
                                <a href="{{ route('dashboard.calendar', ['view' => $viewMode, 'date' => $nextDate]) }}"
                                    class="btn btn-outline-secondary">Next</a>
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
                                                                    {{ $event->modalidad }}</div>
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
                            <h6 class="mb-3">Legend</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary">Class</span>
                                <span class="badge bg-info">Virtual</span>
                                <span class="badge bg-secondary">In-person</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Quick summary</h5>
                        <div class="row text-center mt-3">
                            <div class="col-6 mb-3">
                                <div class="bg-light rounded-3 p-3">
                                    <h3 class="mb-0">{{ $classesThisWeek }}</h3>
                                    <small class="text-muted">Classes this week</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-light rounded-3 p-3">
                                    <h3 class="mb-0">{{ $tasksPending }}</h3>
                                    <small class="text-muted">Pending tasks</small>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6 class="mb-3">For teachers</h6>
                            <p class="small text-muted mb-2">Manage schedules, add new classes, and assign tasks from the dashboard.</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Manage schedule</a>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
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