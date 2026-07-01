@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Crear usuario administrativo</h1>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Volver</a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="alert alert-info">La creación manual de usuarios ya no está disponible. Los usuarios se registran desde el formulario público y reciben el rol Estudiante automáticamente.</div>
            </div>
        </div>
    </div>
@endsection
