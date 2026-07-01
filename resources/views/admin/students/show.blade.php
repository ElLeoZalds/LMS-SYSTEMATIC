@extends('layouts.app')

@section('content')
    @php
        $enrollments = $user->enrollments()->with('training')->get();
        $coursesPurchased = $enrollments->count();
        $trainingsEnrolled = $enrollments->count();
        $certified = $enrollments->filter(function ($enrollment) {
            return strtoupper(trim((string) $enrollment->status)) === 'C';
        })->count();
        $overallProgress = $coursesPurchased > 0 ? (int) round($enrollments->avg(function ($enrollment) {
            return $enrollment->getProgressPercentageAttribute();
        })) : 0;
    @endphp

    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-0 text-gray-800">Ver estudiante</h1>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Volver</a>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="rounded-circle bg-primary bg-gradient text-white d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi bi-person-circle fs-4"></i>
                            </div>
                            <div>
                                <h2 class="h4 mb-0 text-gray-800">{{ $fullName }}</h2>
                                <p class="text-muted mb-0">{{ $user->person->email ?? 'Sin email' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-success px-3 py-2">
                            <i class="bi bi-circle-fill me-1" style="font-size: 0.65rem;"></i>
                            {{ $user->status === 'A' ? 'Activo' : 'Inactivo' }}
                        </span>
                        <span class="badge bg-primary px-3 py-2">
                            <i class="bi bi-person-badge me-1"></i>
                            {{ $roleName === 'Student' ? 'Estudiante' : $roleName }}
                        </span>
                    </div>
                </div>

                <div class="border rounded-4 p-3 p-lg-4 bg-light-subtle mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-info-circle-fill text-primary"></i>
                        <h3 class="h6 mb-0 text-gray-800">Información general</h3>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <label class="form-label fw-bold small text-uppercase text-muted">Username</label>
                                <div class="fs-6 text-gray-800">{{ $user->username ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <label class="form-label fw-bold small text-uppercase text-muted">Fecha de registro</label>
                                <div class="fs-6 text-gray-800">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '—' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <label class="form-label fw-bold small text-uppercase text-muted">Fecha de creación</label>
                                <div class="fs-6 text-gray-800">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '—' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100 bg-white">
                                <label class="form-label fw-bold small text-uppercase text-muted">Fecha de actualización</label>
                                <div class="fs-6 text-gray-800">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border rounded-4 p-3 p-lg-4 bg-white">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-bar-chart-fill text-primary"></i>
                        <h3 class="h6 mb-0 text-gray-800">Resumen académico</h3>
                    </div>
                    <div class="row g-3">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border-0 shadow-sm h-100 rounded-4 bg-primary bg-gradient text-white">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                        <i class="bi bi-journal-bookmark fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="display-6 fw-bold">{{ $coursesPurchased }}</div>
                                        <div class="small text-white-50">Cursos comprados</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border-0 shadow-sm h-100 rounded-4 bg-info bg-gradient text-white">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                        <i class="bi bi-mortarboard fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="display-6 fw-bold">{{ $trainingsEnrolled }}</div>
                                        <div class="small text-white-50">Capacitaciones inscritas</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border-0 shadow-sm h-100 rounded-4 bg-success bg-gradient text-white">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                                        <i class="bi bi-award fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="display-6 fw-bold">{{ $certified }}</div>
                                        <div class="small text-white-50">Certificados obtenidos</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                            <div class="card border-0 shadow-sm h-100 rounded-4 bg-warning bg-gradient text-dark">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-dark bg-opacity-10 p-3">
                                        <i class="bi bi-speedometer2 fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="display-6 fw-bold">{{ $overallProgress }}%</div>
                                        <div class="small text-dark-emphasis">Progreso general</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
