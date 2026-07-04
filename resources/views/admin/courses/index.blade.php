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
                                        <x-status-badge :is-active="$course->isActive()" />
                                    </td>
                                    <td class="align-middle text-end">
                                        <x-action-button type="edit" :route="route('admin.courses.edit', $course->course_id)" />
                                        <x-action-button type="delete" :route="route('admin.courses.destroy', $course->course_id)" label="Desactivar" icon="toggle-off" />
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
        function confirmDeactivate(url) {
            Swal.fire({
                title: '¿Desactivar este curso?',
                text: 'El curso no estará disponible para nuevas capacitaciones ni inscripciones.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f6c23e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, desactivar',
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
