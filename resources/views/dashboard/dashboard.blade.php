@extends('layouts.app')

@section('title', 'Panel')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Panel</h2>
            <p class="text-muted mb-0">Bienvenido de nuevo. Aquí tienes un resumen rápido de tu actividad y cursos.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('dashboard.calendar') }}" class="btn btn-outline-primary btn-sm">Calendario</a>
            <a href="{{ route('dashboard.my-courses') }}" class="btn btn-primary btn-sm">Mis cursos</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card section-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Cursos activos</h6>
                            <h3 class="mb-0">{{ $totalCourses }}</h3>
                        </div>
                        <div class="info-badge">Actual</div>
                    </div>
                    <p class="text-muted mb-0">Continúa con tus cursos actuales y revisa los nuevos recursos disponibles.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card section-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Próxima evaluación</h6>
                            <h3 class="mb-0">{{ $upcomingEvaluations->first()->curso ?? 'Sin eventos' }}</h3>
                        </div>
                        <div class="info-badge">{{ optional($upcomingEvaluations->first())->fechafin ?? 'N/A' }}</div>
                    </div>
                    <p class="text-muted mb-0">Revisa las fechas de entrega y evalúa tu progreso.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card section-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Progreso</h6>
                            <h3 class="mb-0">{{ $activityProgress }}%</h3>
                        </div>
                        <div class="info-badge">En curso</div>
                    </div>
                    <div class="progress rounded-pill mb-2">
                        <div class="progress-bar bg-primary rounded-pill" role="progressbar"
                            style="width: {{ $activityProgress }}%;" aria-valuenow="{{ $activityProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="text-muted mb-0">Sigue avanzando con tus cursos y tareas pendientes.</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card section-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="text-uppercase text-muted mb-2">Notificaciones</h6>
                            <h3 class="mb-0">{{ $upcomingEvaluations->count() }} nuevas</h3>
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
                            <p class="text-muted mb-0">Revisa tus métricas clave y mantente al día con tu plan de estudio.</p>
                        </div>
                        <a href="{{ route('dashboard.my-courses') }}" class="btn btn-outline-primary btn-sm">Ver mis cursos</a>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <h6 class="mb-2">Cursos inscritos</h6>
                                <p class="mb-0 text-muted">Tienes {{ $activeEnrollments }} inscripciones activas.</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <h6 class="mb-2">Evaluaciones próximas</h6>
                                <p class="mb-0 text-muted">{{ $upcomingEvaluations->count() }} evaluaciones activas por revisar.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="mb-3">Cursos más populares</h6>
                        <div class="list-group">
                            @forelse ($popularCourses as $course)
                            <div class="list-group-item list-group-item-action rounded-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>{{ $course->titulo }}</div>
                                    <span class="badge bg-primary">{{ $course->inscritos }} inscritos</span>
                                </div>
                            </div>
                            @empty
                            <div class="list-group-item rounded-3 text-muted">No hay cursos populares aún.</div>
                            @endforelse
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
                        <a href="{{ route('dashboard.calendar') }}" class="list-group-item list-group-item-action rounded-3">Calendario</a>
                        <a href="{{ route('dashboard.my-courses') }}" class="list-group-item list-group-item-action rounded-3">Mis cursos</a>
                        <a href="{{ route('dashboard.progress') }}" class="list-group-item list-group-item-action rounded-3">Progreso</a>
                        <a href="{{ route('dashboard.certificates') }}" class="list-group-item list-group-item-action rounded-3">Certificados</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection