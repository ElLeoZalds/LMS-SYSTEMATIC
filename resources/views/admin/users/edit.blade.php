@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Editar usuario</h1>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Volver</a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" name="full_name" value="{{ old('full_name', trim(($user->person->first_names ?? '') . ' ' . ($user->person->last_names ?? ''))) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="email" value="{{ old('email', $user->person->email ?? '') }}" class="form-control" required>
                        </div>
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
                                    <option value="{{ $role->role_id }}" {{ old('role_id', optional($user->roles->first())->role_id) == $role->role_id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select name="status" class="form-control">
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
