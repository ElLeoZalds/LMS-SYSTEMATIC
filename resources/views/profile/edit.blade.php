@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Mi perfil</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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
            <div class="col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Datos personales</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="first_names">Nombres</label>
                                <input type="text" class="form-control" id="first_names" name="first_names" value="{{ old('first_names', $user->person?->first_names ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="last_names">Apellidos</label>
                                <input type="text" class="form-control" id="last_names" name="last_names" value="{{ old('last_names', $user->person?->last_names ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->person?->email ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Teléfono</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->person?->phone ?? '') }}" maxlength="9" pattern="[0-9]{9}" placeholder="Ej. 987654321">
                                <small class="form-text text-muted">Máximo 9 dígitos.</small>
                            </div>

                            <div class="form-group">
                                <label for="document_number">Número de documento</label>
                                <input type="text" class="form-control" id="document_number" name="document_number" value="{{ old('document_number', $user->person?->document_number ?? '') }}" maxlength="8" pattern="[0-9]{8}" placeholder="Ej. 12345678">
                                <small class="form-text text-muted">8 dígitos.</small>
                            </div>

                            <div class="form-group">
                                <label>Rol</label>
                                <input type="text" class="form-control" value="{{ $user->roles->pluck('name')->implode(', ') }}" disabled>
                            </div>

                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Cambiar contraseña</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="password">Nueva contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirmar contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-outline-primary">Actualizar contraseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const documentInput = document.getElementById('document_number');
            const phoneInput = document.getElementById('phone');

            if (documentInput) {
                documentInput.addEventListener('input', function () {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);
                });
            }

            if (phoneInput) {
                phoneInput.addEventListener('input', function () {
                    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);
                });
            }
        });
    </script>
@endsection
