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
                                <th class="align-middle">Fechas y Horario</th>
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
                                        <div class="fw-bold">{{ optional($training->course)->title ?? 'Sin curso' }}</div>
                                        <div class="text-muted small">
                                            {{ optional($training->teacher->person)->first_names ?? 'Sin nombre' }}
                                            {{ optional($training->teacher->person)->last_names ?? '' }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @if(!$training->start_date && !$training->end_date && !$training->schedule)
                                            <span class="badge bg-light text-secondary border">Pendiente de Programación</span>
                                        @else
                                            <div class="text-muted small">
                                                @if($training->start_date)
                                                    <div><strong>Inicio:</strong> {{ $training->start_date->format('d M Y') }}</div>
                                                @endif
                                                @if($training->end_date)
                                                    <div><strong>Fin:</strong> {{ $training->end_date->format('d M Y') }}</div>
                                                @endif
                                                @if($training->schedule)
                                                    <div><strong>Horario:</strong> {{ $training->schedule }}</div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $badgeClass = match ($training->modality) {
                                                'virtual' => 'bg-primary',
                                                'presential' => 'bg-success',
                                                'hybrid' => 'bg-warning text-dark',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($training->modality) }}</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route('admin.enrollments.create') }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-user-plus"></i>
                                        </a>
                                        <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $training->training_id }}"
                                            data-course="{{ $training->course_id }}" data-teacher="{{ $training->teacher_id }}"
                                            data-modality="{{ $training->modality }}" data-price="{{ $training->price }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ route('admin.trainings.destroy', $training->training_id) }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                        <h5 class="modal-title fw-bold" id="createTrainingModalLabel">Crear Capacitación</h5>
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
                                <label for="teacher_id" class="form-label">Docente</label>
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
                                <label for="schedule" class="form-label">Horario</label>
                                <input type="text" name="schedule" id="schedule" class="form-control" placeholder="ej: Lun-Mie-Vie 18:00-20:00" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Precio</label>
                                <input type="number" name="price" id="price" class="form-control" step="0.01" min="0.01" required>
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

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
             aria-labelledby="editModalLabel" aria-hidden="true"
             data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="editModalLabel">Editar Capacitación</h5>
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
                                <label for="edit_teacher_id" class="form-label">Docente</label>
                                <select name="teacher_id" id="edit_teacher_id" class="form-control" required>
                                    <option value="">Seleccionar docente</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->user_id }}">
                                            {{ $teacher->person->first_names ?? 'Sin nombre' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_modality" class="form-label">Modalidad</label>
                                <select name="modality" id="edit_modality" class="form-control" required>
                                    <option value="virtual">Virtual</option>
                                    <option value="presential">Presencial</option>
                                    <option value="hybrid">Híbrida</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_price" class="form-label">Precio</label>
                                <input type="number" name="price" id="edit_price" class="form-control" step="0.01" required>
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

        <!-- Enroll Student Modal -->
        <div class="modal fade" id="enrollStudentModal" tabindex="-1" role="dialog"
             aria-labelledby="enrollStudentModalLabel" aria-hidden="true"
             data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="enrollStudentModalLabel">Inscribir Alumno en: [Nombre del Curso]</h5>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="enrollStudentForm">
                            @csrf
                            <input type="hidden" name="training_id" id="enroll_training_id">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Seleccionar Alumno</label>
                                <select name="student_id" id="student_id" class="form-control" required>
                                    <option value="">Seleccionar alumno</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->user_id }}">
                                            {{ $student->person->first_names ?? 'Sin nombre' }} {{ $student->person->last_names ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer border-0 bg-light rounded-bottom-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" form="enrollStudentForm" class="btn btn-success">Inscribir</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        $(document).ready(function () {
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const course = this.getAttribute('data-course');
                    const teacher = this.getAttribute('data-teacher');
                    const modality = this.getAttribute('data-modality');
                    const price = this.getAttribute('data-price');

                    document.getElementById('edit_course_id').value = course;
                    document.getElementById('edit_teacher_id').value = teacher;
                    document.getElementById('edit_modality').value = modality;
                    document.getElementById('edit_price').value = price;

                    document.getElementById('editForm').action = `/admin/trainings/${id}`;

                    $('#editModal').modal({backdrop: 'static', keyboard: false});
                });
            });

            document.querySelectorAll('.enroll-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const trainingId = this.getAttribute('data-training-id');
                    const trainingName = this.getAttribute('data-training-name');

                    document.getElementById('enroll_training_id').value = trainingId;
                    document.getElementById('enrollStudentModalLabel').textContent = `Inscribir Alumno en: ${trainingName}`;

                    $('#enrollStudentModal').modal({backdrop: 'static', keyboard: false});
                });
            });

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

            window.confirmDelete = confirmDelete;

            $('#createTrainingForm').on('submit', function (e) {
                e.preventDefault();

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

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: response.message || 'Capacitación creada correctamente',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            backdrop: false
                        });

                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'Error al crear la capacitación';

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: message,
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            backdrop: false
                        });
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            $('#enrollStudentForm').on('submit', function (e) {
                e.preventDefault();

                const $form = $(this);
                const $submitBtn = $form.find('button[type="submit"]');
                const originalText = $submitBtn.html();
                const trainingId = document.getElementById('enroll_training_id').value;

                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Inscribiendo...');

                $.ajax({
                    url: `/admin/trainings/${trainingId}/enroll`,
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (response) {
                        $('#enrollStudentModal').modal('hide');

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: response.success ? 'success' : 'error',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            backdrop: false
                        });

                        if (response.success) {
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function (xhr) {
                        const message = xhr.responseJSON?.message || 'Error al inscribir al alumno';

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: message,
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true,
                            backdrop: false
                        });
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