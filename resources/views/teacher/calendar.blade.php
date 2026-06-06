@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <style>
            .calendar-table {
                table-layout: fixed;
                width: 100%;
            }
            .calendar-table th {
                white-space: nowrap;
                font-size: .8rem;
            }
            .calendar-table td {
                word-wrap: break-word;
                overflow-wrap: break-word;
                hyphens: auto;
                padding: .55rem .45rem;
            }
            .calendar-event-card {
                line-height: 1.1;
            }
            .calendar-event-link {
                display: block;
                color: inherit;
                text-decoration: none;
            }
            .calendar-event-link:hover {
                text-decoration: none;
                opacity: .92;
            }
        </style>
        @php
        $calendarRoute = request()->routeIs('student.*') ? 'student.calendar' : 'teacher.calendar';
    @endphp

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
            <div>
                <h1 class="h3 mb-1 text-gray-800 fw-bold">Calendario</h1>
                <p class="text-muted small mb-0">{{ $fullName }}</p>
                <p class="text-muted small">Vista cuadricular con tareas y evaluaciones de tus cursos.</p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm rounded-3 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route($calendarRoute, ['month' => $selectedMonth->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary btn-sm">Mes anterior</a>
                            <div class="text-center">
                                <h5 class="mb-0">{{ ucfirst($selectedMonth->locale('es')->translatedFormat('F Y')) }}</h5>
                            </div>
                            <a href="{{ route($calendarRoute, ['month' => $selectedMonth->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary btn-sm">Próximo mes</a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm calendar-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        @foreach(['lun.','mar.','mié.','jue.','vie.','sáb.','dom.'] as $weekday)
                                            <th class="text-center py-2 small">{{ $weekday }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($calendar as $week)
                                        <tr>
                                            @foreach($week as $day)
                                                @php
                                                    $dayKey = $day->format('Y-m-d');
                                                    $dayEvents = $events[$dayKey] ?? [];
                                                    $isCurrentMonth = $day->month === $selectedMonth->month;
                                                    $isToday = $day->isSameDay($today);
                                                    $eventsToShow = array_slice($dayEvents, 0, 3);
                                                    $moreCount = max(count($dayEvents) - 3, 0);
                                                @endphp
                                                <td class="align-top" style="min-height: 120px; vertical-align: top; background-color: {{ $isCurrentMonth ? '#fff' : '#f8f9fa' }};">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <span class="fw-bold">{{ $day->day }}</span>
                                                        @if($isToday)
                                                            <span class="badge bg-primary">Hoy</span>
                                                        @endif
                                                    </div>

                                                    @if(count($dayEvents))
                                                        @foreach($eventsToShow as $event)
                                                            @php
                                                                $compactTitle = \Illuminate\Support\Str::limit($event['title'], 26);
                                                            @endphp
                                                            @if(!empty($event['url']))
                                                                <a href="{{ $event['url'] }}" class="calendar-event-link mb-1 p-2 rounded-3 text-wrap d-block" style="font-size: .78rem; line-height: 1.1; background: {{ $event['type'] === 'task' ? '#0dcaf0' : '#ffc107' }}; color: #000; box-shadow: inset 0 0 0 1px rgba(0,0,0,.08);">
                                                                    <div class="fw-bold text-truncate">Vencimiento: {{ $compactTitle }}</div>
                                                                </a>
                                                            @else
                                                                <div class="calendar-event-card mb-1 p-2 rounded-3 text-wrap" style="font-size: .78rem; line-height: 1.1; background: {{ $event['type'] === 'task' ? '#0dcaf0' : '#ffc107' }}; color: #000; box-shadow: inset 0 0 0 1px rgba(0,0,0,.08);">
                                                                    <div class="fw-bold text-truncate">Vencimiento: {{ $compactTitle }}</div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        @if($moreCount)
                                                            <div class="mt-1 small text-muted">+{{ $moreCount }} más</div>
                                                        @endif
                                                    @else
                                                        {{-- No event content when day is empty --}}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6 mb-3">
                <div class="card shadow-sm rounded-3 border-0">
                    <div class="card-body">
                        <h5 class="card-title">Leyenda</h5>
                        <p class="small text-muted mb-3">Este calendario muestra las fechas de entrega de tareas y el rango de apertura de evaluaciones.</p>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><span class="badge bg-info text-dark me-2">&nbsp;</span> Tarea</li>
                            <li><span class="badge bg-warning text-dark me-2">&nbsp;</span> Evaluación</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 mb-3">
                <div class="card shadow-sm rounded-3 border-0">
                    <div class="card-body">
                        <h5 class="card-title">Consejos</h5>
                        <p class="small text-muted mb-2">- Si el evento aparece en gris, pertenece a otro mes.</p>
                        <p class="small text-muted mb-2">- Las evaluaciones muestran su rango completo.</p>
                        <p class="small text-muted mb-0">- Las tareas muestran la fecha y hora de entrega.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
