@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Editar usuario</h1>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Volver</a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombres *</label>
                            <input type="text" name="first_names" value="{{ old('first_names', $user->person->first_names ?? '') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellidos *</label>
                            <input type="text" name="last_names" value="{{ old('last_names', $user->person->last_names ?? '') }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo *</label>
                        <input type="email" name="email" value="{{ old('email', $user->person->email ?? '') }}" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" name="password" class="form-control">
                            <small class="form-text text-muted">Dejar en blanco para mantener la contraseña actual.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Roles asignados *</label>
                            <div class="border rounded p-3">
                                @foreach($roles as $role)
                                    <div class="form-check mb-2">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               name="role_ids[]"
                                               value="{{ $role->role_id }}"
                                               id="role-{{ $role->role_id }}"
                                               {{ in_array($role->role_id, old('role_ids', $user->roles->pluck('role_id')->toArray())) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role-{{ $role->role_id }}">
                                            {{ $role->name }}
                                            @if($role->name === 'Administrator')
                                                <small class="text-muted d-block">Acceso completo al sistema</small>
                                            @elseif($role->name === 'Teacher')
                                                <small class="text-muted d-block">Puede gestionar cursos y evaluaciones</small>
                                            @elseif($role->name === 'Student')
                                                <small class="text-muted d-block">Puede matricularse en cursos</small>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">Un usuario puede tener múltiples roles simultáneamente.</small>
                            @error('role_ids')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado *</label>
                            <select name="status" class="form-control" required>
                                <option value="A" {{ old('status', $user->status) === 'A' ? 'selected' : '' }}>Activo</option>
                                <option value="I" {{ old('status', $user->status) === 'I' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
@endsection
