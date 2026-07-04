@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <x-page-header
            title="Especialidades"
            subtitle="Administra las especialidades con vistas dedicadas y badges de estado consistentes."
            action-route="{{ route('admin.specialties.create') }}"
            action-label="Crear especialidad"
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
                                        <x-status-badge :is-active="$specialty->isActive()" />
                                    </td>
                                    <td class="align-middle text-end">
                                        <x-action-button type="edit" :route="route('admin.specialties.edit', $specialty->specialty_id)" />
                                        <x-action-button type="delete" :route="route('admin.specialties.destroy', $specialty->specialty_id)" label="Desactivar" icon="toggle-off" />
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
                title: '¿Desactivar esta especialidad?',
                text: 'La especialidad dejará de estar disponible para nuevos cursos y capacitaciones.',
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
