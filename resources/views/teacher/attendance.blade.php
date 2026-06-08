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
                <h1 class="h3 mb-4 text-gray-800">Tomar Asistencia</h1>

                <form action="{{ route('teacher.attendance.create') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="training_id" class="form-label fw-bold small text-muted">Capacitación</label>
                        @if(isset($training))
                            <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                            <input type="text" id="training_id" class="form-control" value="{{ $training->course->title }}" readonly>
                        @else
                            <select id="training_id" name="training_id" class="form-select" required>
                                <option value="">Seleccione una capacitación</option>
                                @foreach($trainings ?? [] as $item)
                                    <option value="{{ $item->training_id }}">{{ $item->course->title }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label for="schedule_select" class="form-label fw-bold small text-muted">Sesión (fecha · horario)</label>
                        @if(isset($training) && $training->schedules->count() > 0)
                            <select id="schedule_select" name="schedule_id" class="form-select">
                                <option value="">Seleccione una sesión</option>
                                    @foreach($training->schedules as $s)
                                    <option value="{{ $s->schedule_id }}" data-date="{{ $s->date }}" {{ (old('schedule_id') == $s->schedule_id || $selectedScheduleId == $s->schedule_id) ? 'selected' : '' }}>
                                        {{ $s->date }}{{ $s->start_time ? ' · ' . $s->start_time : '' }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <select id="schedule_select" name="schedule_id" class="form-select">
                                <option value="">No hay sesiones programadas</option>
                            </select>
                        @endif
                    </div>

                </form>

                @if(!isset($training))
                    <div class="alert alert-info mb-4">
                        Selecciona una capacitación y una fecha para cargar el listado de alumnos.
                    </div>
                @endif

                @if(isset($training))
                    <form id="attendance_form" action="{{ route('teacher.attendance.store') }}" method="POST" class="row g-3">
                        @csrf
                        <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                        <input type="hidden" id="post_schedule_id" name="schedule_id" value="{{ $selectedScheduleId ?? request('schedule_id') ?? '' }}">
                        <input type="hidden" id="post_date" name="date" value="{{ request('date') ?? $date ?? date('Y-m-d') }}">
                        <input type="hidden" id="local_time" name="local_time" value="">

                        <div class="row align-items-center mb-4">
                            <div class="col-md-8">
                                <h2 class="h5 mb-0">{{ $training->course->title }}</h2>
                                <p class="text-muted small mb-0">Fecha de asistencia: {{ $date ?? date('Y-m-d') }}</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-success" onclick="markAllPresent()">
                                    <i class="bi bi-check2-all me-1"></i> Marcar Todos como Presentes
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="previousAttendancesBtn" data-training-id="{{ $training->training_id }}">
                                    <i class="bi bi-list-ul me-1"></i> Asistencias anteriores
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-dark small fw-bold">Estudiante</th>
                                        <th class="text-dark small fw-bold text-center"><span class="text-success">✓ Presente</span></th>
                                        <th class="text-dark small fw-bold text-center"><span class="text-danger">✕ Ausente</span></th>
                                        <th class="text-dark small fw-bold text-center"><span class="text-warning">⊘ Justificado</span></th>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                Guardar Asistencia
                            </button>
                        </div>
                    </form>
                    
                    <!-- Modal: Asistencias anteriores -->
                    <div class="modal fade" id="previousAttendancesModal" tabindex="-1" aria-labelledby="previousAttendancesLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="previousAttendancesLabel">Asistencias anteriores</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="previousAttendancesList">Cargando...</div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script>
            function markAllPresent() {
                var radios = document.querySelectorAll('input[type="radio"][value="P"]');
                radios.forEach(function(radio) {
                    radio.checked = true;
                });
            }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const scheduleSelect = document.getElementById('schedule_select');
                const postScheduleId = document.getElementById('post_schedule_id');
                const postDate = document.getElementById('post_date');

                if (scheduleSelect) {
                    scheduleSelect.addEventListener('change', async function() {
                        const val = this.value;
                        postScheduleId.value = val || '';
                        postDate.value = this.options[this.selectedIndex].dataset.date || '';
                        if (!val) return;

                        // Check if attendance already exists for this schedule
                        const url = '{{ route('teacher.attendance.check') }}?schedule_id=' + val;
                        try {
                            const resp = await fetch(url);
                            const json = await resp.json();
                            if (json.exists) {
                                const res = await Swal.fire({
                                    title: 'Asistencia registrada',
                                    text: 'Ya se registró asistencia para esta sesión. ¿Deseas actualizarla?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí, actualizar',
                                    cancelButtonText: 'No, cancelar'
                                });

                                if (res.isConfirmed) {
                                    // Prefill statuses
                                    if (json.attendances && json.attendances.length) {
                                        const map = {};
                                        json.attendances.forEach(a => { map[a.student_id] = a.attendance; });

                                        document.querySelectorAll('input[type="hidden"][name$="[student_id]"]').forEach(function(h) {
                                            const studentId = h.value;
                                            const rowPrefix = h.name.replace(/\[student_id\]$/, '');
                                            const status = map[studentId] || null;
                                            if (status) {
                                                const short = status === 'present' ? 'P' : (status === 'absent' ? 'A' : 'J');
                                                const radio = document.querySelector('input[name="' + rowPrefix + '[status]"][value="' + short + '"]');
                                                if (radio) radio.checked = true;
                                            } else {
                                                const radioDefault = document.querySelector('input[name="' + rowPrefix + '[status]"][value="P"]');
                                                if (radioDefault) radioDefault.checked = true;
                                            }
                                        });
                                    }
                                } else {
                                    // reset selection
                                    scheduleSelect.value = '';
                                    postScheduleId.value = '';
                                    postDate.value = '';
                                }
                            } else {
                                // no previous attendance -> default present
                                document.querySelectorAll('input[type="radio"][value="P"]').forEach(r => r.checked = true);
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    });
                }

                const attendanceForm = document.getElementById('attendance_form');
                if (attendanceForm) {
                    attendanceForm.addEventListener('submit', async function(event) {
                        event.preventDefault();

                        const submitButton = attendanceForm.querySelector('button[type="submit"]');
                        if (submitButton) {
                            submitButton.disabled = true;
                        }

                        const trainingId = attendanceForm.querySelector('input[name="training_id"]').value;
                        const scheduleId = postScheduleId.value || null;
                        const dateValue = postDate.value || null;

                        const localTimeInput = document.getElementById('local_time');
                        if (localTimeInput) {
                            localTimeInput.value = new Date().toTimeString().slice(0, 8);
                        }

                        const attendances = Array.from(attendanceForm.querySelectorAll('input[type="hidden"][name$="[student_id]"]')).map(function(hiddenInput) {
                            const rowPrefix = hiddenInput.name.replace(/\[student_id\]$/, '');
                            const studentId = hiddenInput.value;
                            const statusInput = attendanceForm.querySelector('input[name="' + rowPrefix + '[status]"]:checked');
                            return {
                                student_id: studentId,
                                status: statusInput ? statusInput.value : 'P'
                            };
                        });

                        const payload = {
                            training_id: trainingId,
                            schedule_id: scheduleId,
                            date: dateValue,
                            local_time: localTimeInput ? localTimeInput.value : null,
                            attendances: attendances,
                        };

                        console.log('Attendance payload:', payload);

                        try {
                            const response = await fetch('{{ route('teacher.attendance.store') }}', {
                                method: 'POST',
                                credentials: 'same-origin',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(payload),
                            });

                            const json = await response.json();

                            console.log('Attendance store response', response.status, json);

                            if (!response.ok) {
                                console.error('Attendance store response', response.status, json);
                            }

                            if (json.message) {
                                Swal.fire({
                                    icon: response.ok ? 'success' : 'error',
                                    title: response.ok ? 'Listo' : 'Error',
                                    text: json.message,
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Hubo un error al procesar la asistencia.',
                                });
                            }
                        } catch (error) {
                            console.error('Error sending attendance JSON:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo guardar la asistencia. Revisa la consola de desarrollador.',
                            });
                        } finally {
                            if (submitButton) {
                                submitButton.disabled = false;
                            }
                        }
                    });
                }

                // Previous attendances modal
                const prevBtn = document.getElementById('previousAttendancesBtn');
                if (prevBtn) {
                    prevBtn.addEventListener('click', async function() {
                        const trainingId = this.dataset.trainingId;
                        const url = '{{ url('teacher/attendance/list') }}' + '/' + trainingId;
                        $('#previousAttendancesModal').modal('show');
                        const container = document.getElementById('previousAttendancesList');
                        container.innerHTML = 'Cargando...';
                        try {
                            const resp = await fetch(url);
                            const json = await resp.json();
                            if (json.schedules && json.schedules.length) {
                                let html = '<div class="list-group">';
                                json.schedules.forEach(s => {
                                    html += '<div class="list-group-item d-flex justify-content-between align-items-center">';
                                    html += '<div>';
                                    html += '<div><strong>' + s.date + '</strong></div>';
                                    html += '</div>';
                                    html += '<div><span class="badge bg-primary me-2">' + s.count + ' registros</span>';
                                    html += '<button class="btn btn-sm btn-outline-primary" onclick="showAttendances(' + s.schedule_id + ')">Mostrar</button>';
                                    html += '</div></div>';
                                });
                                html += '</div>';
                                container.innerHTML = html;
                            } else {
                                container.innerHTML = '<div class="alert alert-info">No hay registros de asistencia anteriores.</div>';
                            }
                        } catch (e) {
                            container.innerHTML = '<div class="alert alert-danger">Error al cargar.</div>';
                        }
                    });
                }

                // Show attendances for a schedule
                window.showAttendances = async function(scheduleId) {
                    const container = document.getElementById('previousAttendancesList');
                    container.innerHTML = 'Cargando...';
                    try {
                        const url = '{{ route('teacher.attendance.check') }}?schedule_id=' + scheduleId;
                        const resp = await fetch(url);
                        const json = await resp.json();
                        
                        if (json.attendances && json.attendances.length) {
                            let html = '<button class="btn btn-sm btn-link mb-3" onclick="location.reload()"><i class="bi bi-arrow-left"></i> Volver a sesiones</button>';
                            html += '<div class="table-responsive">';
                            html += '<table class="table table-sm table-bordered">';
                            html += '<thead class="table-light"><tr>';
                            html += '<th>Estudiante</th>';
                            html += '<th class="text-center">Estado</th>';
                            html += '</tr></thead>';
                            html += '<tbody>';
                            json.attendances.forEach(a => {
                                const statusMap = { 'present': '✓ Presente', 'absent': '✕ Ausente', 'late': '⊘ Justificado', 'justified': '⊘ Justificado' };
                                const status = statusMap[a.attendance] || a.attendance;
                                html += '<tr>';
                                html += '<td>' + a.student_name + '</td>';
                                html += '<td class="text-center">' + status + '</td>';
                                html += '</tr>';
                            });
                            html += '</tbody></table></div>';
                            container.innerHTML = html;
                        } else {
                            container.innerHTML = '<button class="btn btn-sm btn-link mb-3" onclick="location.reload()"><i class="bi bi-arrow-left"></i> Volver a sesiones</button>';
                            container.innerHTML += '<div class="alert alert-info">No hay asistencias registradas.</div>';
                        }
                    } catch (e) {
                        container.innerHTML = '<button class="btn btn-sm btn-link mb-3" onclick="location.reload()"><i class="bi bi-arrow-left"></i> Volver a sesiones</button>';
                        container.innerHTML += '<div class="alert alert-danger">Error al cargar.</div>';
                    }
                };
            });
        </script>
@endsection