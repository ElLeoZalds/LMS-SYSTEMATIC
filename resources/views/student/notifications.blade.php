@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-1">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800 fw-bold">Notificaciones</h1>
            <p class="text-muted mb-0">Revisa tus alertas, tareas próximas y novedades de tus capacitaciones.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('student.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver al inicio
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <div>
                    <h2 class="h5 fw-bold text-dark mb-0">Todas tus notificaciones</h2>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('student.notifications', ['type' => 'anuncios']) }}" class="btn btn-sm {{ $typeFilter === 'anuncios' ? 'btn-primary' : 'btn-outline-secondary' }}">Anuncios</a>
                    <a href="{{ route('student.notifications', ['type' => 'tareas']) }}" class="btn btn-sm {{ $typeFilter === 'tareas' ? 'btn-primary' : 'btn-outline-secondary' }}">Tareas</a>
                    <a href="{{ route('student.notifications', ['type' => 'evaluaciones']) }}" class="btn btn-sm {{ $typeFilter === 'evaluaciones' ? 'btn-primary' : 'btn-outline-secondary' }}">Evaluaciones</a>
                    <a href="{{ route('student.notifications', ['type' => 'calificaciones']) }}" class="btn btn-sm {{ $typeFilter === 'calificaciones' ? 'btn-primary' : 'btn-outline-secondary' }}">Calificaciones</a>
                    <a href="{{ route('student.notifications') }}" class="btn btn-sm btn-outline-secondary">Todos</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($paginatedNotifications->isEmpty())
                <div class="text-muted py-4">No tienes notificaciones para mostrar.</div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($paginatedNotifications as $notification)
                        <div class="list-group-item px-0 py-3 d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-light p-2 mt-1">
                                <i class="bi {{ $notification['icon'] ?? 'bi-bell' }} text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $notification['title'] }}</div>
                                        <div class="small text-muted">{{ $notification['message'] }}</div>
                                    </div>
                                    <span class="badge {{ $notification['is_read'] ? 'bg-light text-muted' : 'bg-primary' }}">{{ $notification['is_read'] ? 'Leída' : 'Nuevo' }}</span>
                                </div>
                                <div class="small text-muted mt-2">
                                    {{ $notification['created_at'] instanceof \Carbon\Carbon ? $notification['created_at']->diffForHumans() : '' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        @if($paginatedNotifications->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $paginatedNotifications->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>
@endsection
