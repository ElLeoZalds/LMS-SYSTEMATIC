@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Gestión de Usuarios</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                    <div>
                        <h5 class="mb-1">Personal del sistema</h5>
                        <p class="text-muted mb-0">{{ $administratorsCount + $teachersCount }} usuarios registrados</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input type="text" name="search_name" value="{{ $searchName }}" class="form-control" placeholder="Buscar por nombre">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search_email" value="{{ $searchEmail }}" class="form-control" placeholder="Buscar por correo">
                    </div>
                    <div class="col-md-2">
                        <select name="role_filter" class="form-control">
                            <option value="">Todos</option>
                            <option value="Administrator" {{ $roleFilter === 'Administrator' ? 'selected' : '' }}>Administrador</option>
                            <option value="Teacher" {{ $roleFilter === 'Teacher' ? 'selected' : '' }}>Docente</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status_filter" class="form-control">
                            <option value="">Todos</option>
                            <option value="A" {{ $statusFilter === 'A' ? 'selected' : '' }}>Activo</option>
                            <option value="I" {{ $statusFilter === 'I' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">Filtrar</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Nombre</th>
                                <th class="align-middle">Correo electrónico</th>
                                <th class="align-middle">Rol</th>
                                <th class="align-middle">Estado</th>
                                <th class="align-middle">Último acceso</th>
                                <th class="align-middle text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $fullName = trim(($user->person->first_names ?? 'Sin nombre') . ' ' . ($user->person->last_names ?? ''));
                                    $roleName = optional($user->roles->first())->name ?? 'Sin rol';
                                    $roleLabel = $roleName === 'Administrator' ? 'Administrador' : ($roleName === 'Teacher' ? 'Docente' : $roleName);
                                @endphp
                                <tr>
                                    <td class="align-middle">{{ $fullName }}</td>
                                    <td class="align-middle">{{ $user->person->email ?? 'Sin email' }}</td>
                                    <td class="align-middle">{{ $roleLabel }}</td>
                                    <td class="align-middle">
                                        <span class="badge {{ $user->status === 'A' ? 'bg-success' : 'bg-danger' }}">{{ $user->status === 'A' ? 'Activo' : 'Inactivo' }}</span>
                                    </td>
                                    <td class="align-middle">—</td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-sm btn-info me-1">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="full_name" value="{{ $fullName }}">
                                            <input type="hidden" name="email" value="{{ $user->person->email ?? '' }}">
                                            <input type="hidden" name="role_id" value="{{ optional($user->roles->first())->role_id }}">
                                            <input type="hidden" name="status" value="{{ $user->status === 'A' ? 'I' : 'A' }}">
                                            <button type="button" class="btn btn-sm {{ $user->status === 'A' ? 'btn-warning' : 'btn-success' }}" onclick="confirmUserStatusChange(this.closest('form'))">
                                                <i class="fas {{ $user->status === 'A' ? 'fa-user-slash' : 'fa-user-check' }}"></i> {{ $user->status === 'A' ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">No hay usuarios administrativos para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links() }}
                </div>

                <script>
                    function confirmUserStatusChange(form) {
                        const isActive = form.querySelector('input[name="status"]').value === 'A';
                        const title = isActive ? '¿Deseas desactivar este usuario?' : '¿Deseas activar este usuario?';
                        const text = isActive
                            ? 'El usuario quedará inactivo después de confirmar.'
                            : 'El usuario quedará activo después de confirmar.';

                        Swal.fire({
                            title,
                            text,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: isActive ? '#f6c23e' : '#1cc88a',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: isActive ? 'Sí, desactivar' : 'Sí, activar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    }
                </script>

                @foreach($users as $userModal)
                    @php
                        $modalFullName = trim(($userModal->person->first_names ?? 'Sin nombre') . ' ' . ($userModal->person->last_names ?? ''));
                        $modalRoleId = optional($userModal->roles->first())->role_id;
                    @endphp
                    <div class="modal fade" id="editUserModal{{ $userModal->user_id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel{{ $userModal->user_id }}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('admin.users.update', $userModal->user_id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editUserModalLabel{{ $userModal->user_id }}">Editar usuario</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre completo</label>
                                            <input type="text" name="full_name" value="{{ old('full_name', $modalFullName) }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Correo</label>
                                            <input type="email" name="email" value="{{ old('email', $userModal->person->email ?? '') }}" class="form-control" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nueva contraseña</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Confirmar contraseña</label>
                                                <input type="password" name="password_confirmation" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Rol</label>
                                                <select name="role_id" class="form-control" required>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->role_id }}" {{ old('role_id', $modalRoleId) == $role->role_id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Estado</label>
                                                <select name="status" class="form-control">
                                                    <option value="A" {{ old('status', $userModal->status) === 'A' ? 'selected' : '' }}>Activo</option>
                                                    <option value="I" {{ old('status', $userModal->status) === 'I' ? 'selected' : '' }}>Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection