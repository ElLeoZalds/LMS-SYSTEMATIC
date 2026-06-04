@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        @php
        $calendarRoute = request()->routeIs('student.*') ? 'student.calendar' : 'teacher.calendar';
    @endphp

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
            <div>
                <h1 class="h3 mb-1 text-gray-800 fw-bold">Calendario</h1>
                <p class="text-muted small mb-0">{{ $fullName }}</p>
                <p class="text-muted small">Vista cuadricular con tareas y evaluaciones de tus cursos.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route($calendarRoute, ['month' => $selectedMonth->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary btn-sm">Mes anterior</a>
                <a href="{{ route($calendarRoute, ['month' => $selectedMonth->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary btn-sm">Próximo mes</a>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm rounded-3 border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-0">{{ $selectedMonth->translatedFormat('F Y') }}</h5>
                                <small class="text-muted">Calendario por días</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning text-dark">Evaluación</span>
                                <span class="badge bg-info text-white">Tarea</span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered calendar-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        @foreach(['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'] as $weekday)
                                            <th class="text-center py-2">{{ $weekday }}</th>
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
                                                @endphp
                                                <td class="align-top" style="min-height: 120px; vertical-align: top; background-color: {{ $isCurrentMonth ? '#fff' : '#f8f9fa' }};">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <span class="fw-bold">{{ $day->day }}</span>
                                                        @if($isToday)
                                                            <span class="badge bg-primary">Hoy</span>
                                                        @endif
                                                    </div>

                                                    @if(count($dayEvents))
                                                        @foreach($dayEvents as $event)
                                                            @php
                                                                $compactTitle = \Illuminate\Support\Str::limit($event['title'], 24);
                                                                $compactTraining = \Illuminate\Support\Str::limit($event['training'], 20);
                                                            @endphp
                                                            <div class="mb-2 p-2 rounded text-wrap" style="font-size: .78rem; background: {{ $event['type'] === 'task' ? '#0dcaf0' : '#ffc107' }}; color: #000;">
                                                                <div class="fw-bold text-truncate">{{ $compactTitle }}</div>
                                                                <div class="text-muted small text-truncate">{{ $compactTraining }}</div>
                                                                <div class="small fst-italic text-end">{{ $event['status'] }}</div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="text-muted small">Sin eventos</div>
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

            <div class="col-12 col-lg-4">
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
                <div class="card shadow-sm rounded-3 border-0 mt-3">
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
