@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-9">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-center">Selecciona tu rol</h4>
                    <p class="mb-0 text-center small">Tienes acceso a múltiples roles. Elige con cuál deseas ingresar.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('role.set') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            @foreach($roles as $role)
                                <div class="col-12">
                                    <button type="submit" name="role" value="{{ $role->role_id }}" class="btn btn-outline-primary w-100 text-start p-3">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-{{ $role->name === 'Administrator' ? 'shield-lock' : ($role->name === 'Teacher' ? 'person-workspace' : 'book') }} h3 me-3"></i>
                                            <div>
                                                <div class="fw-bold">{{ $role->name }}</div>
                                                <small class="text-muted">
                                                    @if($role->name === 'Administrator')
                                                        Gestión completa del sistema
                                                    @elseif($role->name === 'Teacher')
                                                        Gestionar cursos, evaluaciones y estudiantes
                                                    @else
                                                        Ver cursos, evaluaciones y calificaciones
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
