@extends('layouts.app')

@section('content')

    <div class="container-fluid px-4 py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Cursos</h1>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCourseModal">
                + Crear curso
            </button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle"></th>
                                <th class="align-middle">Título</th>
                                <th class="align-middle">Detalles</th>
                                <th class="align-middle">Estado</th>
                                <th class="align-middle text-end">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td class="align-middle pe-3">
                                        <div class="avatar-circle rounded-circle bg-avatar-{{ ($loop->index % 4) + 1 }}">
                                            {{ strtoupper(substr($course->title, 0, 1)) }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="fw-bold">{{ $course->title }}</div>
                                        <div class="text-muted small">
                                            {{ optional($course->specialty)->specialty ?? 'Sin especialidad' }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="text-muted small">
                                            Precio: S/ {{ number_format($course->reference_price, 2) }}<br>
                                            Horas: {{ $course->hours_count ?? 0 }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span
                                            class="badge bg-info">{{ $course->hours_count ? $course->hours_count . ' hrs' : 'Sin horas' }}</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $course->course_id }}"
                                            data-title="{{ $course->title }}" data-description="{{ $course->description }}"
                                            data-specialty="{{ $course->specialty_id }}" data-hours="{{ $course->hours_count }}"
                                            data-price="{{ $course->reference_price }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ route('admin.courses.destroy', $course->course_id) }}')">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="createCourseModalLabel">Crear Curso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.courses.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Título</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea name="description" id="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="specialty_id" class="form-label">Especialidad</label>
                                <select name="specialty_id" id="specialty_id" class="form-control" required>
                                    <option value="">Seleccionar especialidad</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty->specialty_id }}">{{ $specialty->specialty }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="hours_count" class="form-label">Horas</label>
                                <input type="number" name="hours_count" id="hours_count" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="reference_price" class="form-label">Precio</label>
                                <input type="number" name="reference_price" id="reference_price" class="form-control"
                                    step="0.01">
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="editModalLabel">Editar Curso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Título</label>
                                <input type="text" name="title" id="edit_title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Descripción</label>
                                <textarea name="description" id="edit_description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit_specialty_id" class="form-label">Especialidad</label>
                                <select name="specialty_id" id="edit_specialty_id" class="form-control" required>
                                    <option value="">Seleccionar especialidad</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty->specialty_id }}">{{ $specialty->specialty }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_hours_count" class="form-label">Horas</label>
                                <input type="number" name="hours_count" id="edit_hours_count" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="edit_reference_price" class="form-label">Precio</label>
                                <input type="number" name="reference_price" id="edit_reference_price" class="form-control"
                                    step="0.01">
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                const description = this.getAttribute('data-description');
                const specialty = this.getAttribute('data-specialty');
                const hours = this.getAttribute('data-hours');
                const price = this.getAttribute('data-price');

                document.getElementById('edit_title').value = title;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_specialty_id').value = specialty;
                document.getElementById('edit_hours_count').value = hours;
                document.getElementById('edit_reference_price').value = price;

                document.getElementById('editForm').action = `/admin/courses/${id}`;

                new bootstrap.Modal(document.getElementById('editModal')).show();
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
    </script>
@endsection