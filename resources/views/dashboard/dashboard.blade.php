@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">Panel de control</h2>
                <p class="text-muted mb-0">Bienvenido de nuevo. Aquí tienes un resumen rápido de tus actividades y cursos.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('dashboard.calendario') }}" class="btn btn-outline-primary btn-sm">Calendario</a>
                <a href="{{ route('dashboard.mis-cursos') }}" class="btn btn-primary btn-sm">Mis cursos</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card section-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2">Cursos activos</h6>
                                <h3 class="mb-0">8</h3>
                            </div>
                            <div class="info-badge">Nivel 2</div>
                        </div>
                        <p class="text-muted mb-0">Continúa con tus cursos actuales y revisa los nuevos recursos
                            disponibles.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card section-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2">Próxima clase</h6>
                                <h3 class="mb-0">Mañana</h3>
                            </div>
                            <div class="info-badge">09:00</div>
                        </div>
                        <p class="text-muted mb-0">Revisa tu calendario para ver los horarios y tareas programadas.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card section-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2">Progreso</h6>
                                <h3 class="mb-0">64%</h3>
                            </div>
                            <div class="info-badge">En curso</div>
                        </div>
                        <div class="progress rounded-pill mb-2">
                            <div class="progress-bar bg-primary rounded-pill" role="progressbar" style="width: 64%;"
                                aria-valuenow="64" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mb-0">Estás avanzando muy bien en tus asignaciones.</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card section-card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="text-uppercase text-muted mb-2">Notificaciones</h6>
                                <h3 class="mb-0">3 nuevas</h3>
                            </div>
                            <div class="info-badge">Reciente</div>
                        </div>
                        <p class="text-muted mb-0">Tienes actualizaciones pendientes en tus cursos y certificados.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-12 col-xl-8">
                <div class="card section-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-4 flex-column flex-md-row gap-3">
                            <div>
                                <h5 class="mb-2">Resumen de actividad</h5>
                                <p class="text-muted mb-0">Consulta tus métricas más importantes y mantente al día con tu
                                    plan de estudio.</p>
                            </div>
                            <a href="{{ route('dashboard.mis-cursos') }}" class="btn btn-outline-primary btn-sm">Ver mis
                                cursos</a>
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100 bg-white">
                                    <h6 class="mb-2">Última clase</h6>
                                    <p class="mb-0 text-muted">Fundamentos de programación, módulo 3.</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="border rounded-3 p-3 h-100 bg-white">
                                    <h6 class="mb-2">Nueva tarea</h6>
                                    <p class="mb-0 text-muted">Revisa tu tarea de evaluación antes de entregarla.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card section-card h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Accesos rápidos</h5>
                        <div class="list-group">
                            <a href="{{ route('dashboard.calendario') }}"
                                class="list-group-item list-group-item-action rounded-3">Calendario</a>
                            <a href="{{ route('dashboard.mis-cursos') }}"
                                class="list-group-item list-group-item-action rounded-3">Mis cursos</a>
                            <a href="#" class="list-group-item list-group-item-action rounded-3">Evaluaciones</a>
                            <a href="#" class="list-group-item list-group-item-action rounded-3">Certificados</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection