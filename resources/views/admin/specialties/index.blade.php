@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Especialidades</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSpecialtyModal">
                + Crear especialidad
            </button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle"></th>
                                <th class="align-middle">Especialidad</th>
                                <th class="align-middle">Detalles</th>
                                <th class="align-middle">Estado</th>
                                <th class="align-middle text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($specialties as $specialty)
                                <tr>
                                    <td class="align-middle pe-3">
                                        <div class="avatar-circle rounded-circle bg-avatar-{{ ($loop->index % 4) + 1 }}">
                                            {{ strtoupper(substr($specialty->specialty, 0, 1)) }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="fw-bold">{{ $specialty->specialty }}</div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="text-muted small">Cursos: {{ $specialty->courses->count() ?? 0 }}</div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-success">Activo</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $specialty->specialty_id }}"
                                            data-specialty="{{ $specialty->specialty }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ route('admin.specialties.destroy', $specialty->specialty_id) }}')">
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
        <div class="modal fade" id="createSpecialtyModal" tabindex="-1" aria-labelledby="createSpecialtyModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="createSpecialtyModalLabel">Crear Especialidad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.specialties.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="specialty" class="form-label">Especialidad</label>
                                <input type="text" name="specialty" id="specialty" class="form-control" required>
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
                        <h5 class="modal-title fw-bold" id="editModalLabel">Editar Especialidad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_specialty" class="form-label">Especialidad</label>
                                <input type="text" name="specialty" id="edit_specialty" class="form-control" required>
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
                const specialty = this.getAttribute('data-specialty');

                document.getElementById('edit_specialty').value = specialty;
                document.getElementById('editForm').action = `/admin/specialties/${id}`;
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