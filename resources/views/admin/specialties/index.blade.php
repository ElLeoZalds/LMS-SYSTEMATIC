@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Especialidades</h1>
                <p class="text-muted mb-0">Administra las especialidades desde un flujo más rápido y consistente.</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#specialtyModal" data-mode="create">
                <i class="fas fa-plus me-1"></i>Crear especialidad
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle"></th>
                                <th class="align-middle">Especialidad</th>
                                <th class="align-middle">Capacitaciones</th>
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
                                        <div class="d-flex gap-2 flex-wrap">
                                            @if($specialty->active_trainings > 0)
                                                <span class="badge badge-primary" data-toggle="tooltip" title="Capacitaciones activas/en curso">
                                                    <i class="fas fa-play-circle"></i> {{ $specialty->active_trainings }}
                                                </span>
                                            @endif
                                            @if($specialty->finished_trainings > 0)
                                                <span class="badge badge-success" data-toggle="tooltip" title="Capacitaciones finalizadas">
                                                    <i class="fas fa-check-circle"></i> {{ $specialty->finished_trainings }}
                                                </span>
                                            @endif
                                            @if($specialty->archived_trainings > 0)
                                                <span class="badge badge-secondary" data-toggle="tooltip" title="Capacitaciones archivadas">
                                                    <i class="fas fa-archive"></i> {{ $specialty->archived_trainings }}
                                                </span>
                                            @endif
                                            @if($specialty->active_trainings === 0 && $specialty->finished_trainings === 0 && $specialty->archived_trainings === 0)
                                                <span class="text-muted small">Ninguna</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <x-status-badge :is-active="$specialty->isActive()" />
                                    </td>
                                    <td class="align-middle text-end">
                                        <button type="button" class="btn btn-sm btn-warning me-2" data-toggle="modal" data-target="#specialtyModal" data-action="edit-specialty" data-id="{{ $specialty->specialty_id }}" data-specialty="{{ $specialty->specialty }}" data-url="{{ route('admin.specialties.update', $specialty->specialty_id) }}">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        @if($specialty->isActive() && $specialty->active_trainings > 0)
                                            <button type="button" class="btn btn-sm btn-outline-danger" disabled data-toggle="tooltip" title="No se puede desactivar: tiene {{ $specialty->active_trainings }} capacitación(es) en curso">
                                                <i class="fas fa-lock"></i> Desactivar
                                            </button>
                                        @else
                                            <form action="{{ route('admin.specialties.toggle-active', $specialty->specialty_id) }}" method="POST" class="d-inline swal-confirm-toggle" data-specialty-id="{{ $specialty->specialty_id }}" data-specialty-name="{{ $specialty->specialty }}" data-can-deactivate-route="{{ route('admin.specialties.can-deactivate', $specialty->specialty_id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $specialty->isActive() ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    <i class="fas {{ $specialty->isActive() ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i> {{ $specialty->isActive() ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="specialtyModal" tabindex="-1" role="dialog" aria-labelledby="specialtyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="specialtyForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="specialtyMethod" value="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="specialtyModalLabel">Crear especialidad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="specialtyName">Nombre de la especialidad</label>
                            <input type="text" class="form-control" id="specialtyName" name="specialty" required maxlength="100" placeholder="Ej. Diseño UX">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();

            const modal = document.getElementById('specialtyModal');
            const form = document.getElementById('specialtyForm');
            const modalTitle = document.getElementById('specialtyModalLabel');
            const inputName = document.getElementById('specialtyName');
            const methodInput = document.getElementById('specialtyMethod');

            document.querySelectorAll('[data-action="edit-specialty"]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const url = button.getAttribute('data-url') || '{{ route('admin.specialties.index') }}';
                    const specialtyName = button.getAttribute('data-specialty') || '';

                    form.setAttribute('action', url);
                    methodInput.value = 'PATCH';
                    inputName.value = specialtyName;
                    modalTitle.textContent = 'Editar especialidad';
                });
            });

            document.querySelector('[data-mode="create"]').addEventListener('click', function () {
                form.setAttribute('action', '{{ route('admin.specialties.store') }}');
                methodInput.value = 'POST';
                inputName.value = '';
                modalTitle.textContent = 'Crear especialidad';
            });

            document.querySelectorAll('form.swal-confirm-toggle').forEach(function (formElement) {
                formElement.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const specialtyId = formElement.getAttribute('data-specialty-id');
                    const specialtyName = formElement.getAttribute('data-specialty-name');

                    // Pre-validar con AJAX
                    const canDeactivateUrl = formElement.getAttribute('data-can-deactivate-route');
                    fetch(canDeactivateUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.can_deactivate) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'No se puede desactivar',
                                    html: `<strong>${specialtyName}</strong> tiene ${data.active_trainings} capacitaciones en curso.<br>` +
                                          `Finalice o archive esas capacitaciones antes de desactivar.`,
                                    confirmButtonText: 'Entendido'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Desactivar especialidad',
                                    html: `¿Desactivar <strong>${specialtyName}</strong>?<br><br>` +
                                          `<small class="text-muted">Capacitaciones finalizadas: ${data.finished_trainings}<br>` +
                                          `Capacitaciones archivadas: ${data.archived_trainings}</small>`,
                                    showCancelButton: true,
                                    confirmButtonText: 'Sí, desactivar',
                                    cancelButtonText: 'Cancelar',
                                    customClass: {
                                        popup: 'swal2-modal swal2-show'
                                    }
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        formElement.submit();
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Hubo un error al verificar la validación.',
                                confirmButtonText: 'Entendido'
                            });
                        });
                });
            });
        });
    </script>

@endsection
