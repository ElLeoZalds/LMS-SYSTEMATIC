@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <x-page-header
            title="Cursos"
            subtitle="Gestiona los cursos desde una experiencia más clara y sin modales de edición."
            action-route="{{ route('admin.courses.create') }}"
            action-label="Crear curso"
            action-icon="plus"
        />

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
                                <th class="align-middle">Título</th>
                                <th class="align-middle">Módulos</th>
                                <th class="align-middle">Capacitaciones</th>
                                <th class="align-middle">Estado</th>
                                <th class="align-middle text-end">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($courses as $course)
                                @php
                                    $activeTrainings = $course->trainings()->where('status', \App\Models\Training::STATUS_ACTIVE)
                                        ->where(function($q) { $q->whereNull('end_date')->orWhere('end_date', '>', now()); })->count();
                                    $finishedTrainings = $course->trainings()->where(function($q) {
                                        $q->where('status', \App\Models\Training::STATUS_FINISHED)
                                            ->orWhere(fn($subQ) => $subQ->where('status', \App\Models\Training::STATUS_ACTIVE)->where('end_date', '<=', now()));
                                    })->count();
                                    $archivedTrainings = $course->trainings()->where('status', \App\Models\Training::STATUS_ARCHIVED)->count();
                                @endphp
                                <tr>
                                    <td class="align-middle pe-3">
                                        <div class="avatar-circle rounded-circle bg-avatar-{{ ($loop->index % 4) + 1 }}">
                                            {{ strtoupper(substr($course->title, 0, 1)) }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.courses.edit', $course->course_id) }}" class="fw-bold text-primary">
                                            {{ $course->title }}
                                        </a>
                                        <div class="text-muted small">
                                            {{ optional($course->specialty)->specialty ?? 'Sin especialidad' }}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-light text-dark">{{ $course->modules_count }} módulo{{ $course->modules_count == 1 ? '' : 's' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex gap-2 flex-wrap">
                                            @if($activeTrainings > 0)
                                                <span class="badge badge-primary" data-toggle="tooltip" title="Capacitaciones activas/en curso">
                                                    <i class="fas fa-play-circle"></i> {{ $activeTrainings }}
                                                </span>
                                            @endif
                                            @if($finishedTrainings > 0)
                                                <span class="badge badge-success" data-toggle="tooltip" title="Capacitaciones finalizadas">
                                                    <i class="fas fa-check-circle"></i> {{ $finishedTrainings }}
                                                </span>
                                            @endif
                                            @if($archivedTrainings > 0)
                                                <span class="badge badge-secondary" data-toggle="tooltip" title="Capacitaciones archivadas">
                                                    <i class="fas fa-archive"></i> {{ $archivedTrainings }}
                                                </span>
                                            @endif
                                            @if($activeTrainings === 0 && $finishedTrainings === 0 && $archivedTrainings === 0)
                                                <span class="text-muted small">Ninguna</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <x-status-badge :is-active="$course->isActive()" />
                                    </td>
                                    <td class="align-middle text-end">
                                        <x-action-button type="edit" :route="route('admin.courses.edit', $course->course_id)" label="Gestionar" />
                                        @if($course->isActive() && $activeTrainings > 0)
                                            <button type="button" class="btn btn-sm btn-outline-danger" disabled data-toggle="tooltip" title="No se puede desactivar: tiene {{ $activeTrainings }} capacitaciones en curso">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('admin.courses.toggle-active', $course->course_id) }}" method="POST" class="d-inline swal-confirm-toggle" data-course-id="{{ $course->course_id }}" data-course-name="{{ $course->title }}" data-can-deactivate-route="{{ route('admin.courses.can-deactivate', $course->course_id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $course->isActive() ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    <i class="fas {{ $course->isActive() ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();

            document.querySelectorAll('form.swal-confirm-toggle').forEach(function (formElement) {
                formElement.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const courseId = formElement.getAttribute('data-course-id');
                    const courseName = formElement.getAttribute('data-course-name');

                    // Pre-validar con AJAX
                    const canDeactivateUrl = formElement.getAttribute('data-can-deactivate-route');
                    fetch(canDeactivateUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.can_deactivate) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'No se puede desactivar',
                                    html: `<strong>${courseName}</strong> tiene ${data.active_trainings} capacitaciones en curso.<br>` +
                                          `Finalice o archive esas capacitaciones antes de desactivar.`,
                                    confirmButtonText: 'Entendido'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Desactivar curso',
                                    html: `¿Desactivar <strong>${courseName}</strong>?<br><br>` +
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
