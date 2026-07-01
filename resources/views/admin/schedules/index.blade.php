@extends('layouts.app')

@section('content')

    <div class="container-fluid px-4 py-1">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Horarios</h1>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createScheduleModal">+ Crear horario</button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-0">
                @php
                    $schedulesByTraining = $schedules->groupBy(function ($schedule) {
                        return trim((optional($schedule->training->course)->title ?? 'Sin capacitación') . optional($schedule->training->start_date)->format(' (Y-m)'));
                    });
                @endphp

                <div class="accordion admin-users-accordion" id="schedulesAccordion">
                    @foreach($schedulesByTraining as $trainingTitle => $trainingSchedules)
                        @php
                            $safeId = preg_replace('/[^A-Za-z0-9]/', '', $trainingTitle);
                        @endphp
                        <div class="accordion-item border-0 shadow-sm mb-3 rounded overflow-hidden">
                            <h2 class="accordion-header" id="heading{{ $safeId }}">
                                <button class="accordion-button collapsed bg-white text-dark fw-semibold py-3 px-4 d-flex align-items-center justify-content-between" type="button" data-toggle="collapse" data-target="#collapse{{ $safeId }}" aria-expanded="false" aria-controls="collapse{{ $safeId }}">
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <span>{{ $trainingTitle }} <small class="text-muted">({{ $trainingSchedules->count() }} horarios)</small></span>
                                    </span>
                                    <span class="badge rounded-pill bg-primary px-3 py-2">{{ $trainingSchedules->count() }}</span>
                                </button>
                            </h2>
                            <div id="collapse{{ $safeId }}" class="accordion-collapse collapse border-top" aria-labelledby="heading{{ $safeId }}" data-parent="#schedulesAccordion">
                                <div class="accordion-body p-0 bg-white">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="align-middle">ID</th>
                                                    <th class="align-middle">Profesor</th>
                                                    <th class="align-middle">Fecha</th>
                                                    <th class="align-middle">Inicio</th>
                                                    <th class="align-middle">Fin</th>
                                                    <th class="align-middle text-end">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($trainingSchedules as $schedule)
                                                    <tr data-schedule-id="{{ $schedule->schedule_id }}" data-training-id="{{ $schedule->training_id }}" data-date="{{ $schedule->date->format('Y-m-d') }}" data-start-time="{{ $schedule->start_time }}" data-end-time="{{ $schedule->end_time }}">
                                                        <td class="align-middle">{{ $schedule->schedule_id }}</td>
                                                        <td class="align-middle">
                                                            {{ optional($schedule->training->teacher->person)->first_names }} {{ optional($schedule->training->teacher->person)->last_names }}
                                                        </td>
                                                        <td class="align-middle">{{ $schedule->date->format('Y-m-d') }}</td>
                                                        <td class="align-middle">{{ $schedule->start_time }}</td>
                                                        <td class="align-middle">{{ $schedule->end_time }}</td>
                                                        <td class="align-middle text-end">
                                                            <button type="button" class="btn btn-sm btn-warning edit-schedule-btn"
                                                                data-schedule-id="{{ $schedule->schedule_id }}"
                                                                data-training-id="{{ $schedule->training_id }}"
                                                                data-date="{{ $schedule->date->format('Y-m-d') }}"
                                                                data-start-time="{{ $schedule->start_time }}"
                                                                data-end-time="{{ $schedule->end_time }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ route('admin.schedules.destroy', $schedule->schedule_id) }}')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- Create Schedule Modal -->
    <div class="modal fade" id="createScheduleModal" tabindex="-1" role="dialog" aria-labelledby="createScheduleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-3">
                <form id="createScheduleForm" action="{{ route('admin.schedules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="modal_type" value="create">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="createScheduleModalLabel">Crear Horario</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create_training_id" class="form-label">Capacitación</label>
                            <select name="training_id" id="create_training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                                <option value="">Seleccionar capacitación</option>
                                @foreach($trainings as $training)
                                    <option value="{{ $training->training_id }}" {{ old('training_id') == $training->training_id ? 'selected' : '' }}>
                                        {{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}
                                        @if(optional($training->teacher->person)->first_names)
                                            - {{ optional($training->teacher->person)->first_names }} {{ optional($training->teacher->person)->last_names }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('training_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="create_date" class="form-label">Fecha</label>
                                <input type="date" name="date" id="create_date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="create_start_time" class="form-label">Hora inicio</label>
                                <input type="time" name="start_time" id="create_start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="create_end_time" class="form-label">Hora fin</label>
                                <input type="time" name="end_time" id="create_end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog" aria-labelledby="editScheduleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-3">
                <form id="editScheduleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal_type" value="edit">
                    <input type="hidden" name="schedule_id" id="edit_schedule_id" value="{{ old('schedule_id') }}">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="editScheduleModalLabel">Editar Horario</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_training_id" class="form-label">Capacitación</label>
                            <select name="training_id" id="edit_training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                                <option value="">Seleccionar capacitación</option>
                                @foreach($trainings as $training)
                                    <option value="{{ $training->training_id }}" {{ old('training_id') == $training->training_id ? 'selected' : '' }}>
                                        {{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}
                                        @if(optional($training->teacher->person)->first_names)
                                            - {{ optional($training->teacher->person)->first_names }} {{ optional($training->teacher->person)->last_names }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('training_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="edit_date" class="form-label">Fecha</label>
                                <input type="date" name="date" id="edit_date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="edit_start_time" class="form-label">Hora inicio</label>
                                <input type="time" name="start_time" id="edit_start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="edit_end_time" class="form-label">Hora fin</label>
                                <input type="time" name="end_time" id="edit_end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = '@csrf @method("DELETE")';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function openEditModal(scheduleId, forcePopulate = false) {
            const row = document.querySelector(`[data-schedule-id="${scheduleId}"]`);
            if (!row) {
                return;
            }

            const form = document.getElementById('editScheduleForm');
            form.action = `/admin/schedules/${scheduleId}`;
            document.getElementById('edit_schedule_id').value = scheduleId;

            const dateField = document.getElementById('edit_date');
            const startTimeField = document.getElementById('edit_start_time');
            const endTimeField = document.getElementById('edit_end_time');
            const trainingField = document.getElementById('edit_training_id');

            if (forcePopulate || !dateField.value) {
                dateField.value = row.dataset.date;
            }
            if (forcePopulate || !startTimeField.value) {
                startTimeField.value = row.dataset.startTime;
            }
            if (forcePopulate || !endTimeField.value) {
                endTimeField.value = row.dataset.endTime;
            }
            if (forcePopulate || !trainingField.value) {
                trainingField.value = row.dataset.trainingId;
            }

            $('#editScheduleModal').modal('show');
        }

        document.querySelectorAll('.edit-schedule-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                openEditModal(this.dataset.scheduleId, true);
            });
        });

        const oldModalType = '{{ old('modal_type') }}';
        const oldScheduleId = '{{ old('schedule_id') }}';

        if (oldModalType === 'create') {
            $('#createScheduleModal').modal('show');
        }

        if (oldModalType === 'edit' && oldScheduleId) {
            openEditModal(oldScheduleId, false);
        }
    </script>

@endsection
