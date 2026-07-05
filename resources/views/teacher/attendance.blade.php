@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        @php
            $attendanceBackUrl = request('training_id') ? route('teacher.courses.show', request('training_id')) . '?tab=asistencias' : route('teacher.courses');
        @endphp
        <div class="mb-4 d-flex justify-content-end">
            <a href="{{ $attendanceBackUrl }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver al Curso
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-4">
                <form id="attendance_selection_form" action="{{ route('teacher.attendance.create') }}" method="GET" class="mb-4">
                    @if(isset($training))
                        <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                    @else
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="training_id" class="form-label fw-bold small text-muted">Seleccione una capacitación</label>
                                <select id="training_id" name="training_id" class="form-select" required>
                                    <option value="">Seleccione una capacitación</option>
                                    @foreach($trainings as $item)
                                        <option value="{{ $item->training_id }}">{{ optional($item->course)->title ?? optional($item->course)->name ?? 'Sin curso' }}{{ optional($item->start_date)->format(' (Y-m)') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <input type="hidden" id="session_schedule_id" name="schedule_id" value="{{ $selectedScheduleId ?? '' }}">
                </form>

                @if(!isset($training))
                    <div class="alert alert-info mb-4">
                        Selecciona una capacitación y una fecha para cargar el listado de alumnos.
                    </div>
                @endif

                @if(isset($training))
                    <div class="row mb-4">
                        <div class="col-12">
                            <h2 class="h3 mb-4 text-gray-800 fw-bold">Tomar Asistencia - {{ $training->course->name ?? optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}</h2>
                        </div>
                    </div>

                    <div class="row gx-3 align-items-center mb-4">
                        <div class="col-md-8 d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-success btn-lg" onclick="markAllPresent()">
                                <i class="bi bi-check2-all me-1"></i> Marcar Todos como Presentes
                            </button>
                            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#calendarModal">
                                <i class="bi bi-calendar3 me-1"></i> Seleccionar Sesión
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg" data-toggle="modal" data-target="#historyModal">
                                <i class="bi bi-clock-history me-1"></i> Ver Histórico
                            </button>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <p class="mb-0 text-muted">
                                Sesión actual: <strong>{{ data_get($selectedSession, 'formattedDate', 'No hay sesión seleccionada') }}</strong>
                            </p>
                        </div>
                    </div>

                    @if($selectedScheduleId)
                        <div class="row">
                            <div class="col-12">
                                <form id="attendance_form" action="{{ route('teacher.attendance.store') }}" method="POST" class="row g-3">
                                    @csrf
                                    <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                                    <input type="hidden" id="post_schedule_id" name="schedule_id" value="{{ $selectedScheduleId ?? request('schedule_id') ?? '' }}">
                                    <input type="hidden" id="post_date" name="date" value="{{ request('date') ?? $date ?? date('Y-m-d') }}">
                                    <input type="hidden" id="local_time" name="local_time" value="">

                                    <div class="table-responsive">
                                        <table class="table table-bordered w-100">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-dark small fw-bold">Estudiante</th>
                                                    <th class="text-dark small fw-bold text-center"><span class="text-success">✓ Presente</span></th>
                                                    <th class="text-dark small fw-bold text-center"><span class="text-danger">✕ Ausente</span></th>
                                                    <th class="text-dark small fw-bold text-center"><span class="text-warning">⊘ Justificado</span></th>
                                                    <th class="text-dark small fw-bold text-center"><span class="text-warning">T Tardanza</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($training->enrollments as $enrollment)
                                                    <tr>
                                                        <td class="align-middle">
                                                            <div class="text-dark">
                                                                {{ optional($enrollment->student->person)->first_names }}
                                                                {{ optional($enrollment->student->person)->last_names }}
                                                            </div>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <input type="radio" name="attendances[{{ $loop->index }}][status]" value="P" class="form-check-input" checked>
                                                            <input type="hidden" name="attendances[{{ $loop->index }}][student_id]" value="{{ $enrollment->student_id }}">
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <input type="radio" name="attendances[{{ $loop->index }}][status]" value="A" class="form-check-input">
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <input type="radio" name="attendances[{{ $loop->index }}][status]" value="J" class="form-check-input">
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <input type="radio" name="attendances[{{ $loop->index }}][status]" value="T" class="form-check-input">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            Guardar Asistencia
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary mb-4">
                            Selecciona una sesión en el calendario para cargar el listado de alumnos y tomar asistencia.
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <style>
            .modal-calendar-grid {
                display: grid;
                grid-template-columns: repeat(7, minmax(0, 1fr));
                gap: 0.35rem;
            }
            .modal-calendar-cell {
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                min-height: 55px;
                background: #fff;
                cursor: pointer;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;
                padding: 0.35rem;
                font-size: 0.85rem;
                transition: transform 0.12s ease, box-shadow 0.12s ease;
            }
            .modal-calendar-cell:hover {
                transform: translateY(-1px);
                box-shadow: 0 0.25rem 0.65rem rgba(0, 0, 0, 0.08);
            }
            .modal-calendar-cell--empty {
                background: transparent;
                border-color: transparent;
                cursor: default;
            }
            .modal-calendar-cell--today {
                border-color: #0d6efd;
            }
            .modal-calendar-cell--selected {
                background: #e9f5ff;
                border-color: #0d6efd;
            }
            .modal-calendar-session-indicator {
                display: block;
                height: 7px;
                width: 7px;
                border-radius: 50%;
                margin-top: 0.15rem;
            }
            .modal-calendar-weekday {
                font-size: 0.75rem;
            }
            .calendar-dot-completed { background: #28a745; }
            .calendar-dot-today { background: #0d6efd; }
            .calendar-dot-pending { background: #fd7e14; }
            .calendar-dot-future { background: #6c757d; }
            .modal-calendar-body {
                max-height: 520px;
                overflow-y: auto;
            }
        </style>

        <div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="calendarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="calendarModalLabel">Seleccionar Sesión - {{ $training->course->name ?? optional($training->course)->title ?? 'Sin curso' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body modal-calendar-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="fw-bold" id="modalCalendarMonthLabel"></div>
                            <div class="btn-group" role="group" aria-label="Navegación de meses">
                                <button type="button" class="btn btn-sm btn-light px-2" id="modalPrevMonthBtn">Mes anterior</button>
                                <button type="button" class="btn btn-sm btn-light px-2" id="modalNextMonthBtn">Próximo mes</button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between text-center text-muted small mb-2">
                            <div class="modal-calendar-weekday">Lun</div>
                            <div class="modal-calendar-weekday">Mar</div>
                            <div class="modal-calendar-weekday">Mie</div>
                            <div class="modal-calendar-weekday">Jue</div>
                            <div class="modal-calendar-weekday">Vie</div>
                            <div class="modal-calendar-weekday">Sab</div>
                            <div class="modal-calendar-weekday">Dom</div>
                        </div>
                        <div id="modalCalendarGrid" class="modal-calendar-grid"></div>
                        <div id="modalCalendarMessage" class="alert alert-warning mt-3 mb-0 d-none" role="alert"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="historyModalLabel">Histórico de Sesiones - {{ $training->course->name ?? optional($training->course)->title ?? 'Sin curso' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Estado</th>
                                        <th class="text-center">Presentes</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allSessions as $session)
                                        <tr>
                                            <td>{{ $session['formattedDate'] ?? $session['date'] }}</td>
                                            <td>{{ $session['formattedTime'] ?? ($session['start_time'] ?? '-') }}</td>
                                            <td>
                                                @php
                                                    $badgeClass = 'badge badge-secondary';
                                                    if ($session['status'] === 'completed') {
                                                        $badgeClass = 'badge badge-success';
                                                    } elseif ($session['status'] === 'pending') {
                                                        $badgeClass = 'badge badge-warning';
                                                    } elseif ($session['status'] === 'future' || $session['status'] === 'today') {
                                                        $badgeClass = 'badge badge-info';
                                                    }
                                                @endphp
                                                <span class="{{ $badgeClass }}">{{ $session['badge_label'] ?? ucfirst($session['status']) }}</span>
                                            </td>
                                            <td class="text-center">{{ $session['attendance_count'] ?? 0 }}/{{ $session['student_count'] ?? 0 }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm history-select-session" data-schedule-id="{{ $session['schedule_id'] }}">
                                                    Seleccionar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function markAllPresent() {
                var radios = document.querySelectorAll('input[type="radio"][value="P"]');
                radios.forEach(function(radio) {
                    radio.checked = true;
                });
            }

            function getLocalToday() {
                var now = new Date();
                var year = now.getFullYear();
                var month = String(now.getMonth() + 1).padStart(2, '0');
                var day = String(now.getDate()).padStart(2, '0');
                return year + '-' + month + '-' + day;
            }

            document.addEventListener('DOMContentLoaded', function() {
                var sessionSelectInput = document.getElementById('session_schedule_id');
                var attendanceSelectionForm = document.getElementById('attendance_selection_form');
                var modalGrid = document.getElementById('modalCalendarGrid');
                var modalMonthLabel = document.getElementById('modalCalendarMonthLabel');
                var prevMonthBtn = document.getElementById('modalPrevMonthBtn');
                var nextMonthBtn = document.getElementById('modalNextMonthBtn');
                var calendarModal = document.getElementById('calendarModal');

                var allSessions = @json($allSessions);
                var sessionMap = allSessions.reduce(function(map, session) {
                    if (session.date) {
                        map[session.date] = session;
                    }
                    return map;
                }, {});

                var today = getLocalToday();
                var currentYear = new Date(today).getFullYear();
                var currentMonth = new Date(today).getMonth() + 1;
                var selectedScheduleId = @json($selectedScheduleId ?? null);
                var historyButtons = document.querySelectorAll('.history-select-session');

                function formatMonthLabel(year, month) {
                    return new Date(year, month - 1, 1).toLocaleDateString('es-ES', { year: 'numeric', month: 'long' });
                }

                function getCalendarDays(year, month) {
                    var firstDay = new Date(year, month - 1, 1);
                    var lastDay = new Date(year, month, 0);
                    var days = [];
                    var startWeekday = (firstDay.getDay() + 6) % 7;
                    for (var blank = 0; blank < startWeekday; blank++) {
                        days.push(null);
                    }
                    for (var day = 1; day <= lastDay.getDate(); day++) {
                        days.push(new Date(year, month - 1, day));
                    }
                    return days;
                }

                function closeModal() {
                    if (window.jQuery && typeof window.jQuery(calendarModal).modal === 'function') {
                        window.jQuery(calendarModal).modal('hide');
                    } else {
                        calendarModal.classList.remove('show');
                        calendarModal.style.display = 'none';
                        document.body.classList.remove('modal-open');
                    }
                }

                function renderModalCalendar() {
                    if (!modalGrid || !modalMonthLabel) {
                        return;
                    }
                    modalMonthLabel.textContent = formatMonthLabel(currentYear, currentMonth);
                    modalGrid.innerHTML = '';

                    var days = getCalendarDays(currentYear, currentMonth);
                    days.forEach(function(dateObj) {
                        var cell = document.createElement('div');
                        cell.className = 'modal-calendar-cell text-center';

                        if (!dateObj) {
                            cell.classList.add('modal-calendar-cell--empty');
                            modalGrid.appendChild(cell);
                            return;
                        }

                        var dateString = dateObj.toISOString().slice(0, 10);
                        var session = sessionMap[dateString];
                        var isToday = dateString === today;

                        var number = document.createElement('div');
                        number.textContent = dateObj.getDate();
                        number.className = 'font-weight-bold';

                        cell.appendChild(number);

                        if (session) {
                            var dot = document.createElement('div');
                            dot.className = 'modal-calendar-session-indicator';
                            if (session.status === 'completed') {
                                dot.classList.add('calendar-dot-completed');
                            } else if (session.status === 'today') {
                                dot.classList.add('calendar-dot-today');
                            } else if (session.status === 'pending') {
                                dot.classList.add('calendar-dot-pending');
                            } else if (session.status === 'future') {
                                dot.classList.add('calendar-dot-future');
                            }
                            cell.appendChild(dot);
                        }

                        if (isToday) {
                            cell.classList.add('modal-calendar-cell--today');
                        }

                        if (session && selectedScheduleId && session.schedule_id === selectedScheduleId) {
                            cell.classList.add('modal-calendar-cell--selected');
                        }

                        cell.addEventListener('click', function() {
                            if (!session) {
                                var messageElement = document.getElementById('modalCalendarMessage');
                                if (messageElement) {
                                    messageElement.textContent = 'No hay sesión programada para este día';
                                    messageElement.classList.remove('d-none');
                                }
                                return;
                            }

                            if (sessionSelectInput) {
                                sessionSelectInput.value = session.schedule_id;
                            }

                            if (attendanceSelectionForm) {
                                attendanceSelectionForm.submit();
                            }

                            closeModal();
                        });

                        modalGrid.appendChild(cell);
                    });
                }

                if (historyButtons) {
                    historyButtons.forEach(function(button) {
                        button.addEventListener('click', function() {
                            var scheduleId = this.getAttribute('data-schedule-id');
                            if (scheduleId && sessionSelectInput) {
                                sessionSelectInput.value = scheduleId;
                            }
                            if (attendanceSelectionForm) {
                                attendanceSelectionForm.submit();
                            }
                            if (window.jQuery && typeof window.jQuery('#historyModal').modal === 'function') {
                                window.jQuery('#historyModal').modal('hide');
                            }
                        });
                    });
                }

                if (prevMonthBtn) {
                    prevMonthBtn.addEventListener('click', function() {
                        currentMonth -= 1;
                        if (currentMonth < 1) {
                            currentMonth = 12;
                            currentYear -= 1;
                        }
                        renderModalCalendar();
                    });
                }

                if (nextMonthBtn) {
                    nextMonthBtn.addEventListener('click', function() {
                        currentMonth += 1;
                        if (currentMonth > 12) {
                            currentMonth = 1;
                            currentYear += 1;
                        }
                        renderModalCalendar();
                    });
                }

                if (window.jQuery && typeof window.jQuery(calendarModal).on === 'function') {
                    window.jQuery(calendarModal).on('shown.bs.modal', function() {
                        renderModalCalendar();
                    });
                }

                renderModalCalendar();
            });
        </script>
@endsection
