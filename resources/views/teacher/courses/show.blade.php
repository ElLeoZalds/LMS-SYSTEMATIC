@extends('layouts.app')

@section('content')
    @php
        use Illuminate\Support\Str;

        $isAdministrator = auth()->user()?->roles->contains('name', 'Administrator') ?? false;
    @endphp

    <div class="teacher-course-view">
        <div class="container-fluid px-4 py-1">
            <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h1 class="h3 mb-2 text-gray-800">{{ optional($training->course)->title ?? 'Sin curso' }}</h1>
                        <p class="text-muted mb-2">{{ optional($training->course->specialty)->specialty ?? 'Sin especialidad' }}</p>
                        <div class="small text-muted">
                            <span class="me-3"><i class="bi bi-code-square me-1"></i>Código: {{ $training->code ?? 'N/A' }}</span>
                            <span class="me-3"><i class="bi bi-clock-history me-1"></i>Modalidad: {{ ucfirst($training->modality) }}</span>
                            <span><i class="bi bi-calendar-event me-1"></i>{{ $training->start_date ? $training->start_date->format('d/m/Y') : 'Sin fecha' }} - {{ $training->end_date ? $training->end_date->format('d/m/Y') : 'Sin fecha' }}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success mb-2">Activo</span>
                        <div class="small text-muted">{{ $totalStudents }} estudiantes matriculados</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-primary h4 mb-2"></i>
                        <h5 class="h6 fw-bold">{{ $totalStudents }}</h5>
                        <small class="text-muted">Estudiantes</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check text-info h4 mb-2"></i>
                        <h5 class="h6 fw-bold">{{ $totalAttendanceRecords }}</h5>
                        <small class="text-muted">Registros de Asistencia</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <span class="badge bg-success p-2 mb-2">Activo</span>
                        <small class="text-muted d-block">Estado del Curso</small>
                    </div>
                </div>
            </div>
        </div>
    <style>
        .course-banner-image { background-position: center center; background-size: cover; width: 100%; }
        .course-banner-fallback { background: #0d6efd; }
        .course-banner-overlay { position: absolute; inset: 0; pointer-events: none; }

        .nav-tabs .nav-item {
            margin-right: 0.75rem;
        }

        .nav-tabs .nav-item:last-child {
            margin-right: 0;
        }

        .teacher-course-view .btn {
            margin-right: 0.3rem;
            margin-bottom: 0.3rem;
        }

        .teacher-course-view .btn:last-child {
            margin-right: 0;
        }

        .student-metric-pass {
            color: #1e8f55;
            font-weight: 600;
        }

        .student-metric-fail {
            color: #d64545;
            font-weight: 600;
        }

        .student-attendance-good {
            color: #1e8f55;
            font-weight: 600;
        }

        .student-attendance-low {
            color: #c97a16;
            font-weight: 600;
        }

        .module-card {
            background: linear-gradient(135deg, #f7f9fc 0%, #eef4fb 100%);
            border: 1px solid #e5ebf2;
            border-left: 4px solid #4f81bd;
            border-radius: 0.75rem;
            padding: 1rem 1rem 1.1rem;
            height: 100%;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .module-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            background: linear-gradient(135deg, #eef5ff 0%, #f7fbff 100%);
        }

        .module-card .module-badge {
            background: rgba(79, 129, 189, 0.14);
            color: #35608f;
            border: 1px solid rgba(79, 129, 189, 0.2);
            padding: 0.35rem 0.65rem;
            border-radius: 999px;
            font-weight: 600;
        }

        .module-card .module-stat-icon {
            color: #4f81bd;
        }

        .module-card .module-stat-icon.tasks {
            color: #d08a23;
        }

        .module-card .module-stat-icon.assessments {
            color: #2f8f61;
        }

        .module-card:nth-child(2n) {
            border-left-color: #5da36b;
        }

        .module-card:nth-child(3n) {
            border-left-color: #d88b3d;
        }

        .module-card:nth-child(4n) {
            border-left-color: #c97c76;
        }

        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        @media print {
            html, body {
                background: #fff !important;
                color: #000 !important;
                margin: 0;
                padding: 0;
            }

            body {
                min-height: auto !important;
                padding: 0 !important;
            }

            #wrapper,
            .sidebar,
            .topbar,
            .sticky-footer,
            .scroll-to-top,
            .navbar-nav,
            .nav-tabs,
            .nav-link,
            .dropdown,
            .dropdown-menu,
            .btn,
            .alert,
            .pagination,
            .modal,
            .modal-backdrop {
                display: none !important;
                visibility: hidden !important;
            }

            .card,
            .card-header,
            .card-body,
            .container-fluid,
            #content,
            #content-wrapper {
                box-shadow: none !important;
                border: none !important;
                background: transparent !important;
            }

            .container-fluid {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 11pt !important;
            }

            table th,
            table td {
                border: 1px solid #000 !important;
                padding: 0.5rem !important;
            }

            thead {
                display: table-header-group !important;
            }

            tfoot {
                display: table-footer-group !important;
            }

            tr {
                page-break-inside: avoid !important;
            }

            .print-report-header {
                display: block !important;
                margin-bottom: 1rem !important;
            }
        }
    </style>

        <div class="card shadow mb-4">
            <div class="card-header bg-white border-bottom">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item me-3" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=inicio"
                            class="nav-link @if(request('tab', 'inicio') === 'inicio') active @endif" id="inicio-tab"
                            role="tab">
                            <i class="bi bi-house-fill me-2"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item me-3" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=estudiantes"
                            class="nav-link @if(request('tab') === 'estudiantes') active @endif" id="estudiantes-tab"
                            role="tab">
                            <i class="bi bi-people-fill me-2"></i>Estudiantes
                        </a>
                    </li>
                    <li class="nav-item me-3" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=asistencias"
                            class="nav-link @if(request('tab') === 'asistencias') active @endif" id="asistencias-tab"
                            role="tab">
                            <i class="bi bi-clipboard-check me-2"></i>Asistencias
                        </a>
                    </li>
                    <li class="nav-item me-3" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=contenido"
                            class="nav-link @if(request('tab') === 'contenido') active @endif" id="contenido-tab"
                            role="tab">
                            <i class="bi bi-book-fill me-2"></i>Evaluaciones
                        </a>
                    </li>
                    <li class="nav-item me-3" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=tareas"
                            class="nav-link @if(request('tab') === 'tareas') active @endif" id="tareas-tab"
                            role="tab">
                            <i class="bi bi-list-task me-2"></i>Tareas
                        </a>
                    </li>
                    <li class="nav-item me-3" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=calificaciones"
                            class="nav-link @if(request('tab') === 'calificaciones') active @endif" id="calificaciones-tab"
                            role="tab">
                            <i class="bi bi-check-circle-fill me-2"></i>Calificaciones
                        </a>
                    </li>
                    <li class="nav-item me-3" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=anuncios"
                            class="nav-link @if(request('tab') === 'anuncios') active @endif" id="anuncios-tab"
                            role="tab">
                            <i class="bi bi-megaphone-fill me-2"></i>Anuncios
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">

                @if(request('tab', 'inicio') === 'inicio')
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0 fw-bold text-dark">Resumen del curso</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 h-100">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="bi bi-info-circle text-primary mt-1"></i>
                                                    <div>
                                                        <h6 class="fw-bold mb-2">Información general</h6>
                                                        <p class="small text-muted mb-1"><strong>Especialidad:</strong> {{ optional($training->course->specialty)->specialty ?? 'Sin especialidad' }}</p>
                                                        <p class="small text-muted mb-1"><strong>Modalidad:</strong> {{ ucfirst($training->modality) }}</p>
                                                        <p class="small text-muted mb-1"><strong>Inicio:</strong> {{ $training->start_date ? $training->start_date->format('d/m/Y') : 'Sin fecha' }}</p>
                                                        <p class="small text-muted mb-0"><strong>Fin:</strong> {{ $training->end_date ? $training->end_date->format('d/m/Y') : 'Sin fecha' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 h-100">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="bi bi-megaphone text-success mt-1"></i>
                                                    <div class="w-100">
                                                        <h6 class="fw-bold mb-2">Últimos anuncios</h6>
                                                        @if($latestAnnouncements->isEmpty())
                                                            <p class="small text-muted mb-0">Aún no hay anuncios publicados para esta capacitación.</p>
                                                        @else
                                                            <ul class="list-unstyled mb-0">
                                                                @foreach($latestAnnouncements as $announcement)
                                                                    <li class="small mb-2">
                                                                        <div class="fw-semibold text-dark">{{ $announcement->title }}</div>
                                                                        <div class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($announcement->message), 90) }}</div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0 fw-bold text-dark">Próximas sesiones</h5>
                                </div>
                                <div class="card-body">
                                    @if($upcomingSchedules->isEmpty())
                                        <div class="alert alert-light border mb-0">No hay sesiones programadas próximas.</div>
                                    @else
                                        <div class="list-group list-group-flush">
                                            @foreach($upcomingSchedules as $schedule)
                                                <div class="list-group-item px-0 py-3">
                                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                                        <div>
                                                            <div class="fw-semibold text-dark">{{ $schedule->date ? $schedule->date->format('d/m/Y') : 'Sin fecha' }}</div>
                                                            <div class="small text-muted">{{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : 'Sin hora' }} - {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : 'Sin hora' }}</div>
                                                        </div>
                                                        <span class="badge bg-primary-subtle text-primary">Programada</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0 fw-bold text-dark">Módulos del curso</h5>
                                </div>
                                <div class="card-body">
                                    @if(($modules ?? collect())->isEmpty())
                                        <div class="alert alert-secondary mb-0">Aún no se han creado módulos para esta capacitación.</div>
                                    @else
                                        <div class="row g-4">
                                            @foreach($modules as $module)
                                                @php
                                                    $moduleKey = $module->module_id ?? $module->id;
                                                    $moduleContents = $training->contents->where('module_id', $moduleKey);
                                                    $moduleTasks = $training->tasks->where('module_id', $moduleKey);
                                                    $moduleAssessments = $training->assessments->where('module_id', $moduleKey);
                                                @endphp
                                                <div class="col-lg-6">
                                                    <div class="module-card h-100">
                                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                                            <div>
                                                                <h6 class="fw-bold mb-1 text-dark">{{ $module->title }}</h6>
                                                                @if(!empty($module->description))
                                                                    <small class="text-muted">{{ $module->description }}</small>
                                                                @endif
                                                            </div>
                                                            <span class="module-badge">{{ $moduleContents->count() + $moduleTasks->count() + $moduleAssessments->count() }} elementos</span>
                                                        </div>
                                                        <div class="mt-3 small text-muted">
                                                            <div class="d-flex gap-3 flex-wrap">
                                                                <span><i class="bi bi-file-earmark-text me-1 module-stat-icon"></i>{{ $moduleContents->count() }} contenidos</span>
                                                                <span><i class="bi bi-list-task me-1 module-stat-icon tasks"></i>{{ $moduleTasks->count() }} tareas</span>
                                                                <span><i class="bi bi-clipboard-check me-1 module-stat-icon assessments"></i>{{ $moduleAssessments->count() }} evaluaciones</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(request('tab') === 'estudiantes')
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Estudiantes Matriculados <span class="badge bg-primary">{{ $students->count() }}</span></h5>
                            <p class="text-muted small mb-0">Directorio simple y filtrable por estado o búsqueda.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <button type="button" id="filterStudentsBtn" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-funnel me-1"></i>Filtrar Estudiantes
                                @if($activeStudentFiltersCount > 0)
                                    <span class="badge bg-info text-dark ms-2">Filtros activos: {{ $activeStudentFiltersCount }}</span>
                                @endif
                            </button>
                            @if($activeStudentFiltersCount > 0)
                                <a href="{{ route('teacher.courses.show', ['id' => $training->training_id, 'tab' => 'estudiantes']) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Limpiar filtros
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" style="min-width: 900px;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre completo</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th><i class="bi bi-person-check me-1"></i>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $enrollment)
                                        @php $isActive = strtoupper((string) $enrollment->status) === 'A'; @endphp
                                        <tr>
                                            <td class="fw-bold">{{ optional($enrollment->student->person)->first_names }} {{ optional($enrollment->student->person)->last_names }}</td>
                                            <td>{{ optional($enrollment->student->person)->document_number }}</td>
                                            <td><small>{{ optional($enrollment->student->person)->email }}</small></td>
                                            <td><small>{{ optional($enrollment->student->person)->phone }}</small></td>
                                            <td>
                                                <span class="badge {{ $isActive ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $isActive ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#student-detail-{{ $enrollment->enrollment_id }}">
                                                    <i class="bi bi-eye me-1"></i>Ver detalle
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @foreach($students as $enrollment)
                            <div class="modal fade" id="student-detail-{{ $enrollment->enrollment_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold text-dark">{{ optional($enrollment->student->person)->first_names }} {{ optional($enrollment->student->person)->last_names }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <div class="border rounded p-3">
                                                        <div class="small text-muted mb-1">Estado de matrícula</div>
                                                        <span class="badge {{ strtoupper((string) $enrollment->status) === 'A' ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ strtoupper((string) $enrollment->status) === 'A' ? 'Activo' : 'Inactivo' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="border rounded p-3">
                                                        <div class="small text-muted mb-1">Contacto</div>
                                                        <div class="small"><strong>Email:</strong> {{ optional($enrollment->student->person)->email ?? 'Sin email' }}</div>
                                                        <div class="small"><strong>Teléfono:</strong> {{ optional($enrollment->student->person)->phone ?? 'Sin teléfono' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info text-center mb-0" role="alert">
                            <i class="bi bi-info-circle me-2"></i>No hay estudiantes matriculados en este curso aún.
                        </div>
                    @endif

                @elseif(request('tab') === 'asistencias')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Registro de Asistencias</h5>
                            <p class="text-muted mb-0">Total de registros: <strong>{{ $totalAttendanceRecords }}</strong></p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('teacher.attendance.create', ['training_id' => $training->training_id]) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-arrow-right me-1"></i>Registrar Asistencia
                            </a>
                            <a href="{{ route('teacher.courses.report.attendance', $training->training_id) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-printer me-1"></i> Imprimir Asistencia
                            </a>
                        </div>
                    </div>

                @elseif(request('tab') === 'contenido')
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-2">Evaluaciones del curso</h5>
                            <p class="text-muted small mb-0">
                                Inicio: <strong>{{ $training->start_date ? \Carbon\Carbon::parse($training->start_date)->format('d/m/Y') : 'Sin fecha' }}</strong>
                                · Fin: <strong>{{ $training->end_date ? \Carbon\Carbon::parse($training->end_date)->format('d/m/Y') : 'Sin fecha' }}</strong>
                            </p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createAssessmentModal">
                                <i class="bi bi-plus-lg me-1"></i>Nueva Evaluación
                            </button>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-bold">Evaluaciones creadas</h6>
                        </div>
                        @if($training->assessments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Título</th>
                                            <th>Módulo</th>
                                            <th class="text-center">Inicio</th>
                                            <th class="text-center">Fin</th>
                                            <th class="text-center">Intentos</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($training->assessments as $assessment)
                                            @php $hasAttempts = $assessment->attempts()->exists(); @endphp
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $assessment->title }}</div>
                                                    @if(!empty($assessment->description))
                                                        <small class="text-muted d-block">{{ $assessment->description }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ optional($assessment->module)->title ?? 'Sin módulo' }}</td>
                                                <td class="text-center">{{ $assessment->start_date ? \Carbon\Carbon::parse($assessment->start_date)->format('d/m/Y') : 'Sin fecha' }}</td>
                                                <td class="text-center">{{ $assessment->end_date ? \Carbon\Carbon::parse($assessment->end_date)->format('d/m/Y') : 'Sin fecha' }}</td>
                                                <td class="text-center">{{ $assessment->allowed_attempts }}</td>
                                                <td class="text-center">
                                                    <span class="badge @if($assessment->active) bg-success @else bg-secondary @endif">{{ $assessment->active ? 'Activo' : 'Inactivo' }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex flex-column align-items-end gap-3">
                                                        <a href="{{ route('teacher.assessments.show', $assessment->assessment_id) }}" class="btn btn-sm btn-info text-white mb-2">
                                                            <i class="bi bi-pencil-square"></i> Gestionar Preguntas
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-primary edit-assessment-btn mb-2" data-assessment='{{ json_encode(["id" => $assessment->assessment_id, "title" => $assessment->title, "description" => $assessment->description, "start_date" => $assessment->start_date ? $assessment->start_date->format('Y-m-d') : null, "end_date" => $assessment->end_date ? $assessment->end_date->format('Y-m-d') : null, "allowed_attempts" => $assessment->allowed_attempts, "time_limit" => $assessment->time_limit ]) }}'>
                                                            <i class="bi bi-pencil"></i> Editar
                                                        </button>
                                                        @if($hasAttempts && ! $isAdministrator)
                                                            <span class="badge bg-warning text-dark mb-2">No se puede eliminar: tiene intentos</span>
                                                        @else
                                                            <form action="{{ route('teacher.assessments.destroy', $assessment->assessment_id) }}" method="POST" class="swal-confirm mb-2" data-message="{{ $hasAttempts ? 'Esta evaluación tiene intentos registrados. Como administrador puedes eliminarla junto con sus datos relacionados. ¿Deseas continuar?' : '¿Estás seguro de eliminar esta evaluación?' }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="bi bi-trash"></i> {{ $hasAttempts ? 'Eliminar como admin' : 'Eliminar' }}
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="card-body text-center text-muted py-4">
                                <i class="bi bi-inbox h3 d-block text-secondary mb-2"></i>
                                <p class="mb-0 small">No hay evaluaciones creadas aún para este curso.</p>
                            </div>
                        @endif
                    </div>

                @elseif(request('tab') === 'tareas')
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-2">Tareas entregables</h5>
                            <p class="text-muted small mb-0">Aquí puedes revisar y administrar las tareas creadas para tus estudiantes.</p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#createTaskModal">
                                <i class="bi bi-plus-lg me-1"></i>Nueva Tarea
                            </button>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-bold">Tareas creadas</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Las tareas entregables asignadas se listan a continuación.</p>
                            @if($training->tasks && $training->tasks->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Título</th>
                                                <th>Módulo</th>
                                                <th class="text-center">Vence</th>
                                                <th class="text-center">Entregas</th>
                                                <th class="text-center">Por revisar</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($training->tasks as $task)
                                                @php $hasSubmissions = $task->submissions->isNotEmpty(); @endphp
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $task->title }}</div>
                                                        @if(!empty($task->description))
                                                            <small class="text-muted d-block">{{ $task->description }}</small>
                                                        @endif
                                                        @if(!empty($task->file_path))
                                                            <small class="d-block mt-1">
                                                                <i class="bi bi-download me-1 text-primary"></i>
                                                                <a href="{{ asset('storage/'.$task->file_path) }}" target="_blank" class="text-decoration-none text-primary fw-semibold">Archivo adjunto</a>
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>{{ optional($task->module)->title ?? 'Sin módulo' }}</td>
                                                    <td class="text-center text-secondary small">{{ $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'Sin fecha' }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border px-2 py-1">{{ $task->submissions->count() }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning text-dark px-2 py-1">{{ $task->submissions->whereNull('grade')->count() }} por revisar</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex flex-column align-items-end gap-3">
                                                            <a href="{{ route('teacher.tasks.submissions', $task->task_id) }}" class="btn btn-sm btn-success mb-2">
                                                                <i class="bi bi-eye"></i> Revisar
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-primary edit-task-btn mb-2" data-task='{{ json_encode(["id" => $task->task_id, "title" => $task->title, "description" => $task->description, "due_date" => $task->due_date ? $task->due_date->format('Y-m-d') : null, "file_path" => $task->file_path ?? null]) }}'>
                                                                <i class="bi bi-pencil"></i> Editar
                                                            </button>
                                                            @if($hasSubmissions && ! $isAdministrator)
                                                                <span class="badge bg-warning text-dark mb-2">No se puede eliminar: tiene entregas</span>
                                                            @else
                                                                <form action="{{ route('teacher.tasks.destroy', $task->task_id) }}" method="POST" class="swal-confirm mb-2" data-message="{{ $hasSubmissions ? 'Esta tarea tiene entregas registradas. Como administrador puedes eliminarla junto con sus datos relacionados. ¿Deseas continuar?' : '¿Deseas eliminar esta tarea?' }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                                        <i class="bi bi-trash"></i> {{ $hasSubmissions ? 'Eliminar como admin' : 'Eliminar' }}
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-3 small">
                                    <i class="bi bi-journal-text h4 d-block text-secondary mb-2"></i>
                                    No hay tareas independientes registradas.
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif(request('tab') === 'anuncios')
                    <div class="row gy-4">
                        <div class="col-lg-5">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0 fw-bold">Nuevo anuncio</h5>
                                </div>
                                <div class="card-body">
                                    @if(session('success'))
                                        <div class="alert alert-success small">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="alert alert-danger small">
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('teacher.courses.announcements.store', $training->training_id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="announcement-content" class="form-label fw-bold">Mensaje</label>
                                            <textarea id="announcement-content" name="content" rows="5" class="form-control" required placeholder="Escribe el anuncio aquí...">{{ old('content') }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="announcement-link" class="form-label fw-bold">Enlace opcional</label>
                                            <input id="announcement-link" name="link" type="url" value="{{ old('link') }}" class="form-control" placeholder="https://ejemplo.com/" />
                                            <small class="text-muted">Puedes agregar un enlace a recursos externos, reuniones o documentos.</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="announcement-attachments" class="form-label fw-bold">Imágenes / Archivos</label>
                                            <input id="announcement-attachments" name="attachments[]" type="file" class="form-control" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.ppt,.pptx,.zip" />
                                            <small class="text-muted">Puedes subir varios archivos, 5 MB por archivo.</small>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Publicar anuncio</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0 fw-bold">Anuncios publicados</h5>
                                        <small class="text-muted">Los estudiantes verán estos mensajes en el curso.</small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($training->announcements->isEmpty())
                                        <div class="alert alert-secondary mb-0">
                                            <i class="bi bi-inbox me-2"></i>No hay anuncios registrados para este curso.
                                        </div>
                                    @else
                                        @foreach($training->announcements->sortByDesc('created_at') as $announcement)
                                            <div class="border rounded-3 p-3 mb-3">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <h6 class="mb-1">Anuncio publicado</h6>
                                                        <small class="text-muted">{{ $announcement->created_at->format('d/m/Y H:i') }}</small>
                                                    </div>
                                                </div>
                                                <div class="mb-3 text-break">
                                                    {!! nl2br(e($announcement->content)) !!}
                                                </div>

                                                @if($announcement->link)
                                                    <div class="mb-3">
                                                        <a href="{{ $announcement->link }}" target="_blank" class="text-decoration-none">
                                                            <i class="bi bi-link-45deg me-1"></i>{{ $announcement->link }}
                                                        </a>
                                                    </div>
                                                @endif

                                                @if($announcement->attachments)
                                                    <div class="row g-2">
                                                        @foreach($announcement->attachments as $attachment)
                                                            <div class="col-12 col-sm-6 col-xl-4">
                                                                <div class="card border">
                                                                    <div class="card-body p-2">
                                                                        @if(strpos($attachment['mime'] ?? '', 'image') !== false)
                                                                            <a href="{{ asset('storage/'.$attachment['path']) }}" target="_blank">
                                                                                <img src="{{ asset('storage/'.$attachment['path']) }}" class="img-fluid rounded mb-2" alt="{{ $attachment['name'] }}">
                                                                            </a>
                                                                        @endif
                                                                        <div class="small">
                                                                            <strong>{{ $attachment['name'] }}</strong>
                                                                        </div>
                                                                        <a href="{{ asset('storage/'.$attachment['path']) }}" target="_blank" class="text-decoration-none small">
                                                                            <i class="bi bi-download me-1"></i>Descargar archivo
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(request('tab') === 'calificaciones')
                    <div class="d-flex justify-content-between align-items-center mb-3 print-report-header">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Gradebook por Módulo</h5>
                            <p class="text-muted mb-0">Curso: {{ optional($training->course)->title ?? 'Sin curso' }} · Módulo: {{ $selectedModule->title ?? 'Sin módulo seleccionado' }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('teacher.courses.report', $training->training_id) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-printer me-1"></i> Imprimir Registro
                            </a>
                            @if($selectedModule)
                                <a href="{{ route('teacher.courses.export-gradebook', ['training' => $training->training_id, 'module_id' => $selectedModule->id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download me-1"></i> Exportar PDF
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body">
                            <label for="moduleSelect" class="form-label fw-bold small text-uppercase">Módulo</label>
                            <select id="moduleSelect" class="form-select form-select-sm" onchange="window.location.href=this.value">
                                <option value="{{ route('teacher.courses.show', ['id' => $training->training_id, 'tab' => 'calificaciones']) }}" {{ ! $moduleId ? 'selected' : '' }}>Seleccionar módulo</option>
                                @foreach($modules as $module)
                                    <option value="{{ route('teacher.courses.show', ['id' => $training->training_id, 'tab' => 'calificaciones', 'module_id' => $module->id]) }}" {{ $moduleId == $module->id ? 'selected' : '' }}>
                                        {{ $module->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($selectedModule)
                        @if($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle text-dark" style="font-size: 0.85rem;">
                                    <thead class="table-light text-center text-uppercase font-weight-bold" style="font-size: 0.75rem;">
                                        <tr>
                                            <th rowspan="2" class="align-middle text-start" style="min-width: 220px;">Estudiante</th>
                                            @if($gradebook['tasks']->count() > 0)
                                                <th colspan="{{ $gradebook['tasks']->count() }}" class="text-info bg-light">Tareas</th>
                                            @endif
                                            @if($gradebook['assessments']->count() > 0)
                                                <th colspan="{{ $gradebook['assessments']->count() }}" class="text-primary bg-light">Evaluaciones</th>
                                            @endif
                                            <th rowspan="2" class="align-middle bg-dark text-white" style="width: 90px;">Promedio</th>
                                        </tr>
                                        <tr>
                                            @foreach($gradebook['tasks'] as $task)
                                                <th class="fw-normal text-truncate small" style="max-width: 110px;" title="{{ $task->title }}">{{ Str::limit($task->title, 12) }}</th>
                                            @endforeach
                                            @foreach($gradebook['assessments'] as $assessment)
                                                <th class="fw-normal text-truncate small" style="max-width: 110px;" title="{{ $assessment->title }}">{{ Str::limit($assessment->title, 12) }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gradebook['rows'] as $row)
                                            <tr>
                                                <td class="fw-bold text-secondary">
                                                    {{ optional($row['student']->person)->first_names }} {{ optional($row['student']->person)->last_names }}
                                                </td>
                                                @foreach($row['cells'] as $cell)
                                                    @php $value = $cell['value']; @endphp
                                                    <td class="text-center @if(! is_null($value)) {{ $value >= 11 ? 'text-success fw-bold' : 'text-danger fw-bold' }} @else text-muted @endif">
                                                        {{ ! is_null($value) ? $value : '-' }}
                                                    </td>
                                                @endforeach
                                                <td class="text-center fw-bold table-light {{ ($row['average'] ?? 0) >= 11 ? 'text-success' : 'text-danger' }}">
                                                    {{ is_null($row['average']) ? '-' : $row['average'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center mb-0" role="alert">
                                <i class="bi bi-info-circle me-2"></i>No hay estudiantes registrados para procesar calificaciones.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-secondary mb-0" role="alert">
                            <i class="bi bi-info-circle me-2"></i>Selecciona un módulo para ver la matriz de calificaciones.
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="createAssessmentModal" tabindex="-1" aria-labelledby="createAssessmentModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="createAssessmentModalLabel">Nueva Evaluación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('teacher.assessments.store') }}" method="POST" id="createAssessmentForm">
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="assessment-module" class="form-label fw-bold">Módulo</label>
                            <select name="module_id" id="assessment-module" class="form-control" required>
                                <option value="">Selecciona un módulo</option>
                                @foreach(($modules ?? collect()) as $module)
                                    <option value="{{ optional($module)->id }}">{{ optional($module)->title }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Selecciona el módulo al que pertenece esta evaluación.</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="assessment-title" class="form-label fw-bold">Título</label>
                            <input type="text" name="title" id="assessment-title" class="form-control" required placeholder="Ej. Examen Parcial I">
                        </div>
                        <div class="form-group mb-3">
                            <label for="assessment-description" class="form-label">Descripción</label>
                            <textarea name="description" id="assessment-description" class="form-control" rows="3" placeholder="Instrucciones breves..."></textarea>
                        </div>
                        <div class="row mb-3">
                            @php
                            $courseMin = now()->toDateString();
                            if ($training->start_date && $training->start_date->gt(now())) {
                                $courseMin = $training->start_date->format('Y-m-d');
                            }
                        @endphp
                        <div class="col-md-6">
                                <label for="assessment-start-date" class="form-label">Fecha de inicio</label>
                                <input type="date" name="start_date" id="assessment-start-date" class="form-control" required min="{{ $courseMin }}" @if($training->end_date) max="{{ $training->end_date->format('Y-m-d') }}" @endif>
                            </div>
                            <div class="col-md-6">
                                <label for="assessment-end-date" class="form-label">Fecha de fin</label>
                                <input type="date" name="end_date" id="assessment-end-date" class="form-control" required min="{{ $courseMin }}" @if($training->end_date) max="{{ $training->end_date->format('Y-m-d') }}" @endif>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="assessment-allowed-attempts" class="form-label">Intentos permitidos</label>
                            <input type="number" name="allowed_attempts" id="assessment-allowed-attempts" class="form-control" min="1" max="3" value="1" required>
                            <small class="form-text text-muted">Entre 1 y 3 intentos.</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="assessment-time-limit" class="form-label">Límite de Tiempo (Minutos)</label>
                            <input type="number" name="time_limit" id="assessment-time-limit" class="form-control" min="20" max="60" value="60" required>
                            <small class="form-text text-muted">Entre 20 y 60 minutos.</small>
                        </div>
                        <div class="form-check mt-3">
                            <input type="checkbox" name="active" id="assessment-active" class="form-check-input" checked value="1">
                            <label class="form-check-label" for="assessment-active">Habilitar inmediatamente</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Evaluación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-success" id="createTaskModalLabel">Nueva Tarea Entregable</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('teacher.tasks.store') }}" method="POST" enctype="multipart/form-data" id="createTaskForm">
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="task-module" class="form-label fw-bold">Módulo</label>
                            <select name="module_id" id="task-module" class="form-control" required>
                                <option value="">Selecciona un módulo</option>
                                @foreach(($modules ?? collect()) as $module)
                                    <option value="{{ optional($module)->id }}">{{ optional($module)->title }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Selecciona el módulo al que pertenece esta tarea.</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="task-title" class="form-label fw-bold">Título de la Tarea</label>
                            <input type="text" name="title" id="task-title" class="form-control" required placeholder="Ej. Informe de Laboratorio 1">
                        </div>
                        <div class="form-group mb-3">
                            <label for="task-description" class="form-label">Indicaciones / Consigna</label>
                            <textarea name="description" id="task-description" class="form-control" rows="3" required placeholder="Describe qué debe subir el alumno..."></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="task-due-date" class="form-label">Fecha de fin</label>
                                <input type="date" name="delivery_date" id="task-due-date" class="form-control" required min="{{ $courseMin }}" @if($training->end_date) max="{{ $training->end_date->format('Y-m-d') }}" @endif>
                            </div>
                            <div class="col-md-6">
                                <label for="task-attachment" class="form-label">Archivo adjunto</label>
                                <input type="file" name="attachment" id="task-attachment" class="form-control" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                                <small class="form-text text-muted">Máx. 5 MB. Tipos permitidos: PDF, DOC, DOCX, TXT, PPT, PPTX, JPG, PNG, ZIP.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success text-white">Publicar Tarea</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDateInput = document.getElementById('assessment-start-date');
            const endDateInput = document.getElementById('assessment-end-date');
            const timeLimitInput = document.getElementById('assessment-time-limit');
            const attemptsInput = document.getElementById('assessment-allowed-attempts');
            const taskAttachmentInput = document.getElementById('task-attachment');
            const taskForm = document.getElementById('createTaskForm');

            const getLocalToday = function() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            const todayDate = getLocalToday();
            if (startDateInput && endDateInput) {
                const assessmentMin = startDateInput.getAttribute('min') || todayDate;
                startDateInput.min = assessmentMin;
                endDateInput.min = assessmentMin;

                startDateInput.addEventListener('change', function() {
                    const minDate = this.value || assessmentMin;
                    endDateInput.min = minDate;
                    if (endDateInput.value && endDateInput.value < endDateInput.min) {
                        endDateInput.value = endDateInput.min;
                    }
                });
            }

            const taskDueDateInput = document.getElementById('task-due-date');
            if (taskDueDateInput) {
                const taskMin = taskDueDateInput.getAttribute('min') || todayDate;
                taskDueDateInput.min = taskMin;
                if (taskDueDateInput.value && taskDueDateInput.value < taskMin) {
                    taskDueDateInput.value = taskMin;
                }
            }

            if (taskAttachmentInput) {
                taskAttachmentInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;
                    const maxSizeMB = 5;
                    if (file.size > maxSizeMB * 1024 * 1024) {
                        Swal.fire({ icon: 'error', title: 'Archivo demasiado grande', text: 'El archivo supera el máximo permitido de 5 MB.' });
                        this.value = '';
                    }
                });
            }

            if (taskForm) {
                taskForm.addEventListener('submit', function(event) {
                    const file = taskAttachmentInput ? taskAttachmentInput.files[0] : null;
                    if (file && file.size > 5 * 1024 * 1024) {
                        event.preventDefault();
                        Swal.fire({ icon: 'error', title: 'Archivo demasiado grande', text: 'El archivo supera el máximo permitido de 5 MB.' });
                        return;
                    }
                });
            }

            if (attemptsInput) {
                attemptsInput.addEventListener('input', function() {
                    let value = Number(this.value);
                    if (value < 1) this.value = 1;
                    if (value > 3) this.value = 3;
                });
            }

            if (timeLimitInput) {
                timeLimitInput.addEventListener('input', function() {
                    let value = Number(this.value);
                    if (value < 20) this.value = 20;
                    if (value > 60) this.value = 60;
                });
            }

            // Edit assessment via modal
            document.querySelectorAll('.edit-assessment-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.getAttribute('data-assessment'));
                    const form = document.getElementById('createAssessmentForm');
                    form.action = '{{ route('teacher.assessments.store') }}'.replace('/assessments', '/assessments/' + data.id);
                    // set method PUT
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    document.getElementById('assessment-title').value = data.title || '';
                    document.getElementById('assessment-description').value = data.description || '';
                    document.getElementById('assessment-start-date').value = data.start_date || '';
                    document.getElementById('assessment-end-date').value = data.end_date || '';
                    document.getElementById('assessment-allowed-attempts').value = data.allowed_attempts || 1;
                    document.getElementById('assessment-time-limit').value = data.time_limit || 60;
                    $('#createAssessmentModal').modal('show');
                });
            });

            // Reset assessment form when creating new
            document.querySelectorAll('button[data-target="#createAssessmentModal"]').forEach(function(b){
                b.addEventListener('click', function(){
                    const form = document.getElementById('createAssessmentForm');
                    form.action = '{{ route('teacher.assessments.store') }}';
                    const methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput) methodInput.remove();
                    form.reset();
                });
            });

            // Edit task via modal
            document.querySelectorAll('.edit-task-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.getAttribute('data-task'));
                    const form = document.getElementById('createTaskForm');
                    form.action = '{{ route('teacher.tasks.store') }}'.replace('/tasks/store', '/tasks/' + data.id);
                    // add method override
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    document.getElementById('task-title').value = data.title || '';
                    document.getElementById('task-description').value = data.description || '';
                    document.getElementById('task-due-date').value = data.due_date || '';
                    $('#createTaskModal').modal('show');
                });
            });

            // Reset task form when creating new
            document.querySelectorAll('button[data-target="#createTaskModal"]').forEach(function(b){
                b.addEventListener('click', function(){
                    const form = document.getElementById('createTaskForm');
                    form.action = '{{ route('teacher.tasks.store') }}';
                    const methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput) methodInput.remove();
                    form.reset();
                });
            });

            const filterStudentsBtn = document.getElementById('filterStudentsBtn');
            if (filterStudentsBtn) {
                filterStudentsBtn.addEventListener('click', function() {
                    const currentUrl = new URL(window.location.href);
                    const currentStatus = currentUrl.searchParams.get('status') || 'all';
                    const currentSearch = currentUrl.searchParams.get('search') || '';
                    const safeSearch = currentSearch.replace(/"/g, '&quot;');

                    Swal.fire({
                        title: 'Filtrar Estudiantes',
                        icon: 'info',
                        html: `
                            <div class="text-start">
                                <label class="form-label fw-bold">Estado</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="student_status" id="student-status-all" value="all" ${currentStatus === 'all' ? 'checked' : ''}>
                                    <label class="form-check-label" for="student-status-all">Todos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="student_status" id="student-status-active" value="active" ${currentStatus === 'active' ? 'checked' : ''}>
                                    <label class="form-check-label" for="student-status-active">Solo Activos</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="student_status" id="student-status-inactive" value="inactive" ${currentStatus === 'inactive' ? 'checked' : ''}>
                                    <label class="form-check-label" for="student-status-inactive">Solo Inactivos</label>
                                </div>
                                <label class="form-label fw-bold mt-3">Buscar por nombre o DNI</label>
                                <input type="text" id="student-search" class="form-control" value="${safeSearch}" placeholder="Ej. María o 12345678">
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Aplicar Filtro',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#4e73df',
                        cancelButtonColor: '#6c757d',
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-secondary'
                        },
                        buttonsStyling: false,
                        preConfirm: function() {
                            const selectedStatus = document.querySelector('input[name="student_status"]:checked')?.value || 'all';
                            const search = document.getElementById('student-search').value.trim();
                            return { status: selectedStatus, search: search };
                        }
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            const nextUrl = new URL(window.location.href);
                            nextUrl.searchParams.set('tab', 'estudiantes');

                            if (result.value.status && result.value.status !== 'all') {
                                nextUrl.searchParams.set('status', result.value.status);
                            } else {
                                nextUrl.searchParams.delete('status');
                            }

                            if (result.value.search) {
                                nextUrl.searchParams.set('search', result.value.search);
                            } else {
                                nextUrl.searchParams.delete('search');
                            }

                            window.location.href = nextUrl.toString();
                        }
                    });
                });
            }

            // SweetAlert confirmation for delete forms
            document.querySelectorAll('form.swal-confirm').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const msg = form.getAttribute('data-message') || '¿Deseas continuar con esta acción?';
                    Swal.fire({
                        title: 'Confirmar eliminación',
                        text: msg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    </div>
@endsection
