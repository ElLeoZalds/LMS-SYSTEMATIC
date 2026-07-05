@extends('layouts.app')

@section('title', 'Panel de administración')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 text-gray-800">Panel de administración</h1>
                <p class="text-muted mb-0">Resumen operativo del sistema con foco en cursos, capacitación y estado de matrícula.</p>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Gestionar cursos
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Estudiantes activos</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people-fill fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Capacitaciones activas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeTrainings }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-book-half fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Matrículas recientes</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $recentEnrollments->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-journal-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Cursos registrados</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCourses }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-grid-fill fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">Matrículas recientes</h5>
                                <small class="text-muted">Últimas inscripciones registradas.</small>
                            </div>
                            <a href="{{ route('admin.enrollments.create') }}" class="btn btn-sm btn-outline-primary">Nueva matrícula</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentEnrollments->isEmpty())
                            <div class="text-muted small">No hay matrículas recientes.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Capacitación</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentEnrollments as $enrollment)
                                            <tr>
                                                <td>{{ optional($enrollment->student->person)->first_names }} {{ optional($enrollment->student->person)->last_names }}</td>
                                                <td>{{ optional($enrollment->training->course)->title ?? 'Sin curso' }}</td>
                                                <td>
                                                    <span class="badge {{ $enrollment->status === 'A' ? 'bg-success' : 'bg-danger' }}">{{ $enrollment->status === 'A' ? 'Activo' : 'Pendiente' }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">Estado del sistema</h5>
                                <small class="text-muted">Especialidades y cursos recientes.</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSpecialties as $specialty)
                                        <tr>
                                            <td><span class="badge bg-info text-dark">Especialidad</span></td>
                                            <td>{{ $specialty->specialty }}</td>
                                            <td>
                                                <span class="badge {{ $specialty->isActive() ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $specialty->isActive() ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @foreach($recentCourses as $course)
                                        <tr>
                                            <td><span class="badge bg-primary">Curso</span></td>
                                            <td>{{ $course->title }}</td>
                                            <td>
                                                <span class="badge {{ $course->isActive() ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $course->isActive() ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection