@extends('layouts.app')

@section('content')

    <div class="container-fluid px-4 py-1">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-4 text-gray-800">Capacitaciones</h1>
            </div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createTrainingModal"
                    data-backdrop="static" data-keyboard="false">
                + Crear capacitación
            </button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle"></th>
                                <th class="align-middle">Capacitación</th>
                                <th class="align-middle">Fechas y modalidad</th>
                                <th class="align-middle">Estado</th>
                                <th class="align-middle text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trainings as $training)
                                <tr>
                                    <td class="align-middle pe-3">
                                        <div class="avatar-circle rounded-circle bg-avatar-{{ ($loop->index % 4) + 1 }}">
                                            {{ strtoupper(substr(optional($training->course)->title ?? 'C', 0, 1)) }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="fw-bold">{{ optional($training->course)->title ?? 'Sin curso asignado' }}{{ optional($training->start_date)->format(' (Y-m)') }}</div>
                                        <div class="text-muted small">
                                            Código: {{ $training->code }}<br>
                                            {{ optional($training->teacher->person)->first_names ?? 'Sin nombre registrado' }}
                                            {{ optional($training->teacher->person)->last_names ?? '' }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @if(!$training->start_date && !$training->end_date && !$training->schedule)
                                            <span class="badge bg-light text-secondary border">Pendiente de programación</span>
                                        @else
                                            <div class="text-muted small">
                                                @if($training->start_date)
                                                    <div><strong>Inicio:</strong> {{ $training->start_date->format('d M Y') }}</div>
                                                @endif
                                                @if($training->end_date)
                                                    <div><strong>Fin:</strong> {{ $training->end_date->format('d M Y') }}</div>
                                                @endif
                                                <div><strong>Modalidad:</strong> {{ ucfirst($training->modality) }}</div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $isActive = (int) $training->status === 1;
                                            $badgeClass = $isActive ? 'bg-success' : 'bg-secondary';
                                            $badgeText = $isActive ? 'Activo' : 'Inactivo';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route('teacher.courses.show', $training->training_id) }}" class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-chalkboard-teacher"></i> Gestionar
                                        </a>
                                        <button class="btn btn-sm btn-info schedule-btn" data-training-id="{{ $training->training_id }}" data-training-name="{{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}" data-teacher-name="{{ optional($training->teacher->person)->first_names ?? 'Sin nombre' }} {{ optional($training->teacher->person)->last_names ?? '' }}" data-start-date="{{ $training->start_date ? $training->start_date->format('Y-m-d') : '' }}" data-end-date="{{ $training->end_date ? $training->end_date->format('Y-m-d') : '' }}">
                                            <i class="fas fa-calendar-plus"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $training->training_id }}"
                                            data-course="{{ $training->course_id }}" data-teacher="{{ $training->teacher_id }}"
                                            data-start-date="{{ $training->start_date ? $training->start_date->format('Y-m-d') : '' }}"
                                            data-end-date="{{ $training->end_date ? $training->end_date->format('Y-m-d') : '' }}"
                                            data-modality="{{ $training->modality }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <x-action-button type="toggle" :route="route('admin.trainings.toggle-active', $training->training_id)" :is-active="$training->isActive()" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay capacitaciones activas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="createTrainingModal" tabindex="-1" role="dialog"
             aria-labelledby="createTrainingModalLabel" aria-hidden="true"
             data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="createTrainingModalLabel">Crear capacitación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.trainings.store') }}" id="createTrainingForm">
                            @csrf
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Curso</label>
                                <select name="course_id" id="course_id" class="form-control" required>
                                    <option value="">Seleccionar curso</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="teacher_id" class="form-label">Profesor</label>
                                <select name="teacher_id" id="teacher_id" class="form-control" required>
                                    <option value="">Seleccionar docente</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->user_id }}">
                                            {{ $teacher->person->first_names ?? 'Sin nombre' }} {{ $teacher->person->last_names ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Fecha de Inicio</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">Fecha de Fin</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="modality" class="form-label">Modalidad</label>
                                <select name="modality" id="modality" class="form-control" required>
                                    <option value="">Seleccionar modalidad</option>
                                    <option value="virtual">Virtual</option>
                                    <option value="presential">Presencial</option>
                                    <option value="hybrid">Híbrida</option>
                                </select>
                            </div>
                            <div class="mb-0">
                                <small class="text-muted">El administrador se asignará automáticamente y el estado inicial será Activo.</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" form="createTrainingForm" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="bulkScheduleModal" tabindex="-1" role="dialog" aria-labelledby="bulkScheduleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="bulkScheduleModalLabel">Agregar horarios</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="bulkScheduleForm" method="POST" action="{{ route('admin.schedules.bulk-store') }}">
                            @csrf
                            <input type="hidden" name="training_id" id="bulk_schedule_training_id">

                            <div class="mb-3 p-3 border rounded bg-light">
                                <div class="fw-bold" id="bulkScheduleTrainingName">Capacitación</div>
                                <div class="text-muted small" id="bulkScheduleTeacherName"></div>
                            </div>

                            <div id="bulkScheduleRows"></div>

                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addScheduleRowBtn">
                                    <i class="fas fa-plus me-1"></i>Agregar otra fila
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" form="bulkScheduleForm" class="btn btn-primary">Guardar horarios</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
             aria-labelledby="editModalLabel" aria-hidden="true"
             data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="editModalLabel">Editar capacitación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_course_id" class="form-label">Curso</label>
                                <select name="course_id" id="edit_course_id" class="form-control" required>
                                    <option value="">Seleccionar curso</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_teacher_id" class="form-label">Profesor</label>
                                <select name="teacher_id" id="edit_teacher_id" class="form-control" required>
                                    <option value="">Seleccionar docente</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->user_id }}">
                                            {{ $teacher->person->first_names ?? 'Sin nombre' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_start_date" class="form-label">Fecha de Inicio</label>
                                    <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_end_date" class="form-label">Fecha de Fin</label>
                                    <input type="date" name="end_date" id="edit_end_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_modality" class="form-label">Modalidad</label>
                                <select name="modality" id="edit_modality" class="form-control" required>
                                    <option value="virtual">Virtual</option>
                                    <option value="presential">Presencial</option>
                                    <option value="hybrid">Híbrida</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" form="editForm" class="btn btn-primary">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        $(document).ready(function () {
            let scheduleRowIndex = 0;
            let trainingStartDate = '';
            let trainingEndDate = '';

            function getLocalDateString(date) {
                const offset = date.getTimezoneOffset();
                const localDate = new Date(date.getTime() - offset * 60000);
                return localDate.toISOString().slice(0, 10);
            }

            function applyTrainingDateLimits(startInput, endInput) {
                const today = getLocalDateString(new Date());
                startInput.min = today;

                if (startInput.value && startInput.value < today) {
                    startInput.value = today;
                }

                const effectiveStart = startInput.value || today;
                endInput.min = effectiveStart;

                if (endInput.value && endInput.value < effectiveStart) {
                    endInput.value = effectiveStart;
                }
            }

            function validateTrainingDateRange(startInput, endInput) {
                const today = getLocalDateString(new Date());
                const startValue = startInput.value;
                const endValue = endInput.value;

                if (!startValue || !endValue) {
                    return { valid: false, message: 'Debe completar ambas fechas.' };
                }

                if (startValue < today) {
                    return { valid: false, message: 'La fecha de inicio no puede ser anterior a la fecha actual.' };
                }

                if (endValue < startValue) {
                    return { valid: false, message: 'La fecha de fin no puede ser anterior a la fecha de inicio.' };
                }

                return { valid: true };
            }

            function buildScheduleRow(index) {
                const minAttr = trainingStartDate ? `min="${trainingStartDate}"` : '';
                const maxAttr = trainingEndDate ? `max="${trainingEndDate}"` : '';
                return `
                    <div class="schedule-row border rounded p-3 mb-3" data-index="${index}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="fw-semibold">Horario ${index + 1}</div>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-schedule-row-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="schedules[${index}][date]" class="form-control schedule-date-input" ${minAttr} ${maxAttr} required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hora inicio</label>
                                <input type="time" name="schedules[${index}][start_time]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hora fin</label>
                                <input type="time" name="schedules[${index}][end_time]" class="form-control" required>
                            </div>
                        </div>
                    </div>
                `;
            }

            function resetBulkScheduleRows() {
                scheduleRowIndex = 0;
                $('#bulkScheduleRows').html(buildScheduleRow(scheduleRowIndex));
            }

            function addBulkScheduleRow() {
                scheduleRowIndex += 1;
                $('#bulkScheduleRows').append(buildScheduleRow(scheduleRowIndex));
            }

            $(document).on('click', '#addScheduleRowBtn', function () {
                addBulkScheduleRow();
            });

            $(document).on('click', '.remove-schedule-row-btn', function () {
                const rows = $('#bulkScheduleRows .schedule-row');
                if (rows.length === 1) {
                    return;
                }

                $(this).closest('.schedule-row').remove();
            });

            document.querySelectorAll('.schedule-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const trainingId = this.getAttribute('data-training-id');
                    const trainingName = this.getAttribute('data-training-name');
                    const teacherName = this.getAttribute('data-teacher-name');
                    trainingStartDate = this.getAttribute('data-start-date') || '';
                    trainingEndDate = this.getAttribute('data-end-date') || '';

                    document.getElementById('bulk_schedule_training_id').value = trainingId;
                    document.getElementById('bulkScheduleTrainingName').textContent = trainingName;
                    document.getElementById('bulkScheduleTeacherName').textContent = teacherName;

                    resetBulkScheduleRows();
                    $('#bulkScheduleModal').modal({backdrop: 'static', keyboard: false});
                });
            });

            const createStartDate = document.getElementById('start_date');
            const createEndDate = document.getElementById('end_date');

            if (createStartDate && createEndDate) {
                applyTrainingDateLimits(createStartDate, createEndDate);
                createStartDate.addEventListener('change', function () {
                    applyTrainingDateLimits(createStartDate, createEndDate);
                });
                createEndDate.addEventListener('change', function () {
                    if (createStartDate.value) {
                        createEndDate.min = createStartDate.value;
                    }
                });
            }

            const editStartDate = document.getElementById('edit_start_date');
            const editEndDate = document.getElementById('edit_end_date');

            if (editStartDate && editEndDate) {
                applyTrainingDateLimits(editStartDate, editEndDate);
                editStartDate.addEventListener('change', function () {
                    applyTrainingDateLimits(editStartDate, editEndDate);
                });
                editEndDate.addEventListener('change', function () {
                    if (editStartDate.value) {
                        editEndDate.min = editStartDate.value;
                    }
                });
            }

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const course = this.getAttribute('data-course');
                    const teacher = this.getAttribute('data-teacher');
                    const startDate = this.getAttribute('data-start-date');
                    const endDate = this.getAttribute('data-end-date');
                    const modality = this.getAttribute('data-modality');

                    document.getElementById('edit_course_id').value = course;
                    document.getElementById('edit_teacher_id').value = teacher;
                    document.getElementById('edit_start_date').value = startDate;
                    document.getElementById('edit_end_date').value = endDate;
                    document.getElementById('edit_modality').value = modality;

                    document.getElementById('editForm').action = `/admin/trainings/${id}`;

                    $('#editModal').modal({backdrop: 'static', keyboard: false});
                });
            });

            $('#bulkScheduleForm').on('submit', function (e) {
                e.preventDefault();

                // Validar que todas las fechas estén dentro del rango
                if (trainingStartDate && trainingEndDate) {
                    const startDate = new Date(trainingStartDate);
                    const endDate = new Date(trainingEndDate);
                    let isValid = true;
                    let invalidDates = [];

                    document.querySelectorAll('.schedule-date-input').forEach((input, index) => {
                        if (input.value) {
                            const selectedDate = new Date(input.value);
                            if (selectedDate < startDate || selectedDate > endDate) {
                                isValid = false;
                                invalidDates.push(`Horario ${index + 1}`);
                                input.classList.add('is-invalid');
                            } else {
                                input.classList.remove('is-invalid');
                            }
                        }
                    });

                    if (!isValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Fechas inválidas',
                            html: `Las siguientes fechas están fuera del rango:<br><strong>${invalidDates.join(', ')}</strong><br><br>Deben estar entre <strong>${startDate.toLocaleDateString('es-ES')}</strong> y <strong>${endDate.toLocaleDateString('es-ES')}</strong>`,
                            confirmButtonText: 'Entendido'
                        });
                        return;
                    }
                }

                const $form = $(this);
                const $submitBtn = $form.find('button[type="submit"]');
                const originalText = $submitBtn.html();

                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Guardando...');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (response) {
                        $('#bulkScheduleModal').modal('hide');

                        SwalToast.fire({ icon: 'success', title: response.message || 'Horarios guardados correctamente' });

                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'Error al guardar los horarios';

                        SwalToast.fire({ icon: 'error', title: message });
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            $('#createTrainingForm').on('submit', function (e) {
                e.preventDefault();

                const startInput = document.getElementById('start_date');
                const endInput = document.getElementById('end_date');
                const validation = validateTrainingDateRange(startInput, endInput);

                if (!validation.valid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Fechas inválidas',
                        text: validation.message,
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                const $form = $(this);
                const $submitBtn = $form.find('button[type="submit"]');
                const originalText = $submitBtn.html();

                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Guardando...');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (response) {
                        $('#createTrainingModal').modal('hide');

                        SwalToast.fire({ icon: 'success', title: response.message || 'Capacitación creada correctamente' });

                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'Error al crear la capacitación';

                        SwalToast.fire({ icon: 'error', title: message });
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            $('#editForm').on('submit', function (e) {
                e.preventDefault();

                const startInput = document.getElementById('edit_start_date');
                const endInput = document.getElementById('edit_end_date');
                const validation = validateTrainingDateRange(startInput, endInput);

                if (!validation.valid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Fechas inválidas',
                        text: validation.message,
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                const $form = $(this);
                const $submitBtn = $form.find('button[type="submit"]');
                const originalText = $submitBtn.html();

                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Actualizando...');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (response) {
                        $('#editModal').modal('hide');

                        SwalToast.fire({ icon: 'success', title: response.message || 'Capacitación actualizada correctamente' });

                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'Error al actualizar la capacitación';

                        SwalToast.fire({ icon: 'error', title: message });
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

        });
    </script>
    @endpush
@endsection
