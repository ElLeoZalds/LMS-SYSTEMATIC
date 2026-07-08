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
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <h1 class="h3 mb-0 text-gray-800">{{ optional($training->course)->title ?? 'Sin curso' }}</h1>
                            @if($training->isFinished())
                                <span class="badge bg-warning text-dark">Finalizada</span>
                            @endif
                        </div>
                        <p class="text-muted mb-2">{{ optional($training->course->specialty)->specialty ?? 'Sin especialidad' }}</p>
                        <div class="small text-muted">
                            <span class="me-3"><i class="bi bi-code-square me-1"></i>Código: {{ $training->code ?? 'N/A' }}</span>
                            <span class="me-3"><i class="bi bi-clock-history me-1"></i>Modalidad: {{ ucfirst($training->modality) }}</span>
                            <span><i class="bi bi-calendar-event me-1"></i>{{ $training->start_date ? $training->start_date->format('d/m/Y') : 'Sin fecha' }} - {{ $training->end_date ? $training->end_date->format('d/m/Y') : 'Sin fecha' }}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge {{ $training->isFinished() ? 'bg-warning text-dark' : 'bg-success' }} mb-2">{{ $training->isFinished() ? 'Finalizada' : 'Activa' }}</span>
                        <div class="small text-muted">{{ $totalStudents }} estudiantes matriculados</div>
                    </div>
                </div>
            </div>
        </div>

        @if($training->isFinished())
            <div class="alert alert-warning border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Capacitación finalizada el {{ optional($training->end_date)->format('d/m/Y') ?? 'sin fecha' }}.</strong>
                        Solo puedes consultar información; no se permiten cambios ni nuevas acciones.
                    </div>
                </div>
            </div>
        @endif

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
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-light border-0 py-2 px-3">
                                    <h4 class="h6 fw-bold text-dark mb-0">Información del curso</h4>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Curso</div>
                                            <div class="fw-bold text-dark">{{ optional($training->course)->title ?? 'Sin curso' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Especialidad</div>
                                            <div class="fw-bold text-dark">{{ optional($training->course->specialty)->specialty ?? 'Sin especialidad' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Modalidad</div>
                                            <div class="fw-bold text-dark">{{ ucfirst($training->modality) }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Fechas</div>
                                            <div class="fw-bold text-dark">{{ $training->start_date ? $training->start_date->format('d/m/Y') : 'Sin fecha' }} - {{ $training->end_date ? $training->end_date->format('d/m/Y') : 'Sin fecha' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Estudiantes</div>
                                            <div class="fw-bold text-dark">{{ $totalStudents }} matriculados</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Estado</div>
                                            <div class="fw-bold text-dark">{{ $training->isActive() ? 'Activo' : 'Inactivo' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-light border-0 py-2 px-3">
                                    <h4 class="h6 fw-bold text-dark mb-0">Próximas sesiones</h4>
                                </div>
                                <div class="card-body p-3">
                                    @if($upcomingSchedules->isEmpty())
                                        <p class="text-muted mb-0">No hay sesiones programadas</p>
                                    @else
                                        <div class="list-group list-group-flush">
                                            @foreach($upcomingSchedules->take(5) as $schedule)
                                                <div class="list-group-item border-0 px-0 py-2 bg-transparent">
                                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $schedule->date ? $schedule->date->format('d/m/Y') : 'Sin fecha' }}</div>
                                                            <div class="small text-muted">{{ $schedule->date ? \Carbon\Carbon::parse($schedule->date)->translatedFormat('l') : 'Sin día' }}</div>
                                                        </div>
                                                        <div class="text-muted small">{{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : 'Sin hora' }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-light border-0 py-2 px-3 d-flex justify-content-between align-items-center">
                                    <h4 class="h6 fw-bold text-dark mb-0">Módulos del curso</h4>
                                    <span class="badge badge-secondary">{{ ($modules ?? collect())->count() }} módulos</span>
                                </div>
                                <div class="card-body p-3">
                                    @if(($modules ?? collect())->isEmpty())
                                        <p class="text-muted mb-0">No hay módulos registrados</p>
                                    @else
                                        <div class="accordion" id="teacherModulesAccordion">
                                            @foreach($modules as $index => $module)
                                                @php
                                                    $moduleKey = $module->module_id ?? $module->id;
                                                    $moduleContents = $module->contents ?? collect();
                                                    $moduleTasks = $module->tasks ?? collect();
                                                    $moduleAssessments = $module->assessments ?? collect();
                                                @endphp
                                                <div class="card mb-3 border-0 shadow-sm">
                                                    <div class="card-header bg-light p-3" id="teacherModuleHeading{{ $moduleKey }}" data-toggle="collapse" data-target="#teacherModuleCollapse{{ $moduleKey }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="teacherModuleCollapse{{ $moduleKey }}" role="button" style="cursor:pointer;">
                                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                                            <div>
                                                                <div class="fw-bold text-dark">Módulo {{ $module->order }} • {{ $module->title }}</div>
                                                                <div class="small text-muted">
                                                                    {{ $moduleContents->count() }} contenido(s) · {{ $moduleAssessments->count() }} evaluación(es) · {{ $moduleTasks->count() }} tarea(s)
                                                                </div>
                                                            </div>
                                                            @if($training->isFinished())
                                                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No se puede modificar una capacitación finalizada">
                                                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled aria-disabled="true">
                                                                        <i class="bi bi-plus-circle me-1"></i>Agregar Contenido
                                                                    </button>
                                                                </span>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="openContentModal({{ $moduleKey }})">
                                                                    <i class="bi bi-plus-circle me-1"></i>Agregar Contenido
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div id="teacherModuleCollapse{{ $moduleKey }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="teacherModuleHeading{{ $moduleKey }}" data-parent="#teacherModulesAccordion">
                                                        <div class="card-body p-3">
                                                            @if($moduleContents->isEmpty())
                                                                <div class="border rounded bg-light p-3 text-center text-muted small">
                                                                    <i class="bi bi-journal-text me-1"></i>No hay contenidos aún en este módulo.
                                                                </div>
                                                            @else
                                                                <div class="list-group list-group-flush">
                                                                    @foreach($moduleContents as $content)
                                                                        <div class="list-group-item border-0 px-0 py-2 bg-transparent">
                                                                            <div class="d-flex justify-content-between align-items-start gap-2">
                                                                                <div class="d-flex gap-2">
                                                                                    <i class="bi bi-file-earmark-text text-primary mt-1"></i>
                                                                                    <div>
                                                                                        <div class="fw-bold text-dark">{{ $content->title }}</div>
                                                                                        @if(! empty($content->description))
                                                                                            <div class="small text-muted">{{ Str::limit($content->description, 90) }}</div>
                                                                                        @endif
                                                                                        <div class="small text-muted mt-1">
                                                                                            <span class="badge badge-light text-muted">{{ $content->type ?? 'Material' }}</span>
                                                                                            @if(! empty($content->file_path))
                                                                                                <span class="ms-2"><i class="bi bi-paperclip"></i> Archivo adjunto</span>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex gap-2">
                                                                                    @if($training->isFinished())
                                                                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No se puede modificar una capacitación finalizada">
                                                                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled aria-disabled="true">
                                                                                                <i class="bi bi-pencil me-1"></i>Editar
                                                                                            </button>
                                                                                        </span>
                                                                                    @else
                                                                                        <button type="button" class="btn btn-sm btn-outline-primary edit-content-btn" data-content='{{ json_encode(["id" => $content->content_id, "module_id" => $content->module_id, "title" => $content->title, "description" => $content->description, "type" => $content->type, "video_url" => $content->video_url, "has_attachment" => !empty($content->file_path)]) }}'>
                                                                                            <i class="bi bi-pencil me-1"></i>Editar
                                                                                        </button>
                                                                                    @endif
                                                                                    @if($training->isFinished())
                                                                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No se puede modificar una capacitación finalizada">
                                                                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled aria-disabled="true">
                                                                                                <i class="bi bi-trash me-1"></i>Eliminar
                                                                                            </button>
                                                                                        </span>
                                                                                    @else
                                                                                        <form action="{{ route('teacher.contents.destroy', $content->content_id) }}" method="POST" class="swal-confirm d-inline" data-message="Esta acción eliminará el contenido y no se podrá deshacer.">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                                                <i class="bi bi-trash me-1"></i>Eliminar
                                                                                            </button>
                                                                                        </form>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
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
                                                <span class="badge {{ $isActive ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $isActive ? 'Activo' : 'Inactivo' }}
                                                </span>
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
                                                        <span class="badge {{ strtoupper((string) $enrollment->status) === 'A' ? 'bg-success' : 'bg-danger' }}">
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
                            <h5 class="fw-bold text-dark mb-2">Contenido y Evaluaciones</h5>
                            <p class="text-muted small mb-0">Organiza el material de estudio y las evaluaciones por módulo para cada bloque del curso.</p>
                        </div>
                    </div>

                    <div class="accordion" id="courseContentAccordion">
                        @forelse(($modules ?? collect()) as $index => $module)
                            @php
                                $moduleStatus = $module->module_status ?? 'Pendiente';
                                $moduleStatusClass = $moduleStatus === 'En curso' ? 'success' : ($moduleStatus === 'Completado' ? 'secondary' : 'info');
                                $moduleBorderColor = $moduleStatus === 'En curso' ? '#28a745' : ($moduleStatus === 'Completado' ? '#6c757d' : '#17a2b8');
                            @endphp
                            <div class="card mb-3" style="border-left: 4px solid {{ $moduleBorderColor }};">
                                <div class="card-header bg-white p-3" id="contentHeading{{ $module->module_id ?? $module->id }}" data-toggle="collapse" data-target="#contentCollapse{{ $module->module_id ?? $module->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="contentCollapse{{ $module->module_id ?? $module->id }}" role="button" style="cursor: pointer;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-journal-bookmark-fill me-3 text-primary"></i>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark">Módulo {{ $module->order }}: {{ $module->title }}</h6>
                                                <small class="text-muted">
                                                    <span class="badge badge-{{ $moduleStatusClass }} me-2">{{ $moduleStatus }}</span>
                                                    {{ $module->contents_count ?? 0 }} material(es) · {{ $module->assessments_count ?? 0 }} evaluación(es)
                                                </small>
                                            </div>
                                        </div>
                                        <i class="bi bi-chevron-{{ $index === 0 ? 'up' : 'down' }}"></i>
                                    </div>
                                </div>
                                <div id="contentCollapse{{ $module->module_id ?? $module->id }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="contentHeading{{ $module->module_id ?? $module->id }}" data-parent="#courseContentAccordion">
                                    <div class="card-body p-4">
                                        <div class="pt-1">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0 fw-bold text-dark">Evaluaciones</h6>
                                                @if($training->isFinished())
                                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No se puede modificar una capacitación finalizada">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled aria-disabled="true">
                                                            <i class="bi bi-plus-circle"></i> Nueva Evaluación
                                                        </button>
                                                    </span>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="openAssessmentModal({{ $module->module_id ?? $module->id }})">
                                                        <i class="bi bi-plus-circle"></i> Nueva Evaluación
                                                    </button>
                                                @endif
                                            </div>
                                            @if(($module->assessments_count ?? 0) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Título</th>
                                                                <th>Fecha límite</th>
                                                                <th>Estado</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($module->assessments as $assessment)
                                                                @php $hasAttempts = $assessment->attempts()->whereNotNull('submitted_at')->exists(); @endphp
                                                                <tr style="border-left: 4px solid {{ $hasAttempts ? '#f59e0b' : 'transparent' }};">
                                                                    <td>
                                                                        <div class="fw-bold">{{ $assessment->title }}</div>
                                                                        @if(!empty($assessment->description))
                                                                            <small class="text-muted d-block">{{ Str::limit($assessment->description, 60) }}</small>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $assessment->end_date ? \Carbon\Carbon::parse($assessment->end_date)->format('d/m/Y') : 'Sin fecha' }}</td>
                                                                    <td>
                                                                        <div class="d-flex flex-column gap-1">
                                                                            <span class="badge @if($assessment->active) bg-success @else bg-danger @endif">{{ $assessment->active ? 'Activa' : 'Inactiva' }}</span>
                                                                            @if($hasAttempts)
                                                                                <span class="badge badge-warning text-dark">Bloqueada</span>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex flex-wrap gap-2">
                                                                            @if($hasAttempts)
                                                                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Esta evaluación ya tiene intentos de estudiantes y no puede modificarse">
                                                                                    <button type="button" class="btn btn-sm btn-info text-white disabled" onclick="return false;" aria-disabled="true">
                                                                                        <i class="bi bi-question-circle"></i> Preguntas
                                                                                    </button>
                                                                                </span>
                                                                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Esta evaluación ya tiene intentos de estudiantes y no puede modificarse">
                                                                                    <button type="button" class="btn btn-sm btn-outline-secondary disabled" onclick="return false;" aria-disabled="true">
                                                                                        <i class="bi bi-pencil"></i> Editar
                                                                                    </button>
                                                                                </span>
                                                                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Esta evaluación ya tiene intentos de estudiantes y no puede modificarse">
                                                                                    <button type="button" class="btn btn-sm btn-outline-secondary disabled" onclick="return false;" aria-disabled="true">
                                                                                        <i class="bi bi-trash"></i> Eliminar
                                                                                    </button>
                                                                                </span>
                                                                            @else
                                                                                <a href="{{ route('teacher.assessments.show', $assessment->assessment_id) }}" class="btn btn-sm btn-info text-white">
                                                                                    <i class="bi bi-question-circle"></i> Preguntas
                                                                                </a>
                                                                                <button type="button" class="btn btn-sm btn-outline-primary edit-assessment-btn" data-assessment='{{ json_encode(["id" => $assessment->assessment_id, "module_id" => $assessment->module_id, "title" => $assessment->title, "description" => $assessment->description, "start_date" => $assessment->start_date ? $assessment->start_date->format('Y-m-d') : null, "end_date" => $assessment->end_date ? $assessment->end_date->format('Y-m-d') : null, "allowed_attempts" => $assessment->allowed_attempts, "time_limit" => $assessment->time_limit, "has_attempts" => $hasAttempts ]) }}'>
                                                                                    <i class="bi bi-pencil"></i> Editar
                                                                                </button>
                                                                                <form action="{{ route('teacher.assessments.destroy', $assessment->assessment_id) }}" method="POST" class="swal-confirm" data-message="¿Estás seguro de eliminar esta evaluación?">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                                                        <i class="bi bi-trash"></i> Eliminar
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
                                                <div class="text-center py-3 border rounded bg-light">
                                                    <i class="bi bi-journal-x h3 d-block text-muted mb-2"></i>
                                                    <p class="text-muted mb-0">No hay evaluaciones en este módulo</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-2"></i>No hay módulos disponibles para esta capacitación.
                            </div>
                        @endforelse
                    </div>

                @elseif(request('tab') === 'tareas')
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-2">Tareas por Módulo</h5>
                            <p class="text-muted small mb-0">Revisa y administra las tareas organizadas por módulo del curso.</p>
                        </div>
                    </div>

                    <div class="accordion" id="courseTasksAccordion">
                        @forelse(($modules ?? collect()) as $index => $module)
                            @php
                                $moduleStatus = $module->module_status ?? 'Pendiente';
                                $moduleStatusClass = $moduleStatus === 'En curso' ? 'success' : ($moduleStatus === 'Completado' ? 'secondary' : 'info');
                                $moduleBorderColor = $moduleStatus === 'En curso' ? '#28a745' : ($moduleStatus === 'Completado' ? '#6c757d' : '#17a2b8');
                            @endphp
                            <div class="card mb-3" style="border-left: 4px solid {{ $moduleBorderColor }};">
                                <div class="card-header bg-white p-3" id="tasksHeading{{ $module->module_id ?? $module->id }}" data-toggle="collapse" data-target="#tasksCollapse{{ $module->module_id ?? $module->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="tasksCollapse{{ $module->module_id ?? $module->id }}" role="button" style="cursor: pointer;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold text-dark">Módulo {{ $module->order }}: {{ $module->title }}</h6>
                                            <small class="text-muted">
                                                <span class="badge badge-{{ $moduleStatusClass }} me-2">{{ $moduleStatus }}</span>
                                                {{ $module->tasks_count ?? 0 }} tarea(s)
                                            </small>
                                        </div>
                                        <i class="bi bi-chevron-{{ $index === 0 ? 'up' : 'down' }}"></i>
                                    </div>
                                </div>
                                <div id="tasksCollapse{{ $module->module_id ?? $module->id }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="tasksHeading{{ $module->module_id ?? $module->id }}" data-parent="#courseTasksAccordion">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-1">Tareas de este módulo</h6>
                                                <small class="text-muted">Crea y gestiona las entregas para este módulo.</small>
                                            </div>
                                            @if($training->isFinished())
                                                <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No se puede modificar una capacitación finalizada">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled aria-disabled="true">
                                                        <i class="bi bi-plus-lg me-1"></i>Crear Tarea
                                                    </button>
                                                </span>
                                            @else
                                                <button type="button" class="btn btn-sm btn-success" onclick="event.stopPropagation(); openTaskModal({{ $module->module_id ?? $module->id }})">
                                                    <i class="bi bi-plus-lg me-1"></i>Crear Tarea
                                                </button>
                                            @endif
                                        </div>
                                        @if(($module->tasks_count ?? 0) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0 align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Título</th>
                                                            <th>Vence</th>
                                                            <th>Entregas</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($module->tasks as $task)
                                                            @php $hasSubmissions = $task->submissions->isNotEmpty(); @endphp
                                                            <tr>
                                                                <td>
                                                                    <div class="fw-bold">{{ $task->title }}</div>
                                                                    @if(!empty($task->description))
                                                                        <small class="text-muted d-block">{{ Str::limit($task->description, 80) }}</small>
                                                                    @endif
                                                                </td>
                                                                <td class="text-secondary small">{{ $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'Sin fecha' }}</td>
                                                                <td>
                                                                    <span class="badge bg-light text-dark border px-2 py-1">{{ $task->submissions->count() }}</span>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex flex-wrap gap-2">
                                                                        <a href="{{ route('teacher.tasks.submissions', $task->task_id) }}" class="btn btn-sm btn-success">
                                                                            <i class="bi bi-eye"></i> Revisar
                                                                        </a>
                                                                        <button type="button" class="btn btn-sm btn-primary edit-task-btn" data-task='{{ json_encode(["id" => $task->task_id, "module_id" => $task->module_id, "title" => $task->title, "description" => $task->description, "due_date" => $task->due_date ? $task->due_date->format('Y-m-d') : null, "file_path" => $task->file_path ?? null]) }}'>
                                                                            <i class="bi bi-pencil"></i> Editar
                                                                        </button>
                                                                        @if($hasSubmissions && ! $isAdministrator)
                                                                            <button type="button" class="btn btn-sm btn-danger" onclick="Swal.fire({ icon: 'warning', title: 'No es posible eliminar', text: 'Esta tarea ya tiene entregas registradas y no puede eliminarse.' });">
                                                                                <i class="bi bi-trash"></i> Eliminar
                                                                            </button>
                                                                        @else
                                                                            <form action="{{ route('teacher.tasks.destroy', $task->task_id) }}" method="POST" class="swal-confirm" data-message="{{ $hasSubmissions ? 'Esta tarea tiene entregas registradas. Como administrador puedes eliminarla junto con sus datos relacionados. ¿Deseas continuar?' : '¿Deseas eliminar esta tarea?' }}">
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
                                            <div class="text-center py-3 border rounded bg-light">
                                                <i class="bi bi-journal-text h3 d-block text-muted mb-2"></i>
                                                <p class="text-muted mb-0">No hay tareas en este módulo</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-2"></i>No hay módulos disponibles para esta capacitación.
                            </div>
                        @endforelse
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

                                        @if($training->isFinished())
                                            <button type="button" class="btn btn-primary" disabled aria-disabled="true" data-toggle="tooltip" title="No se puede modificar una capacitación finalizada">Publicar anuncio</button>
                                        @else
                                            <button type="submit" class="btn btn-primary">Publicar anuncio</button>
                                        @endif
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

    <div class="modal fade" id="createContentModal" tabindex="-1" aria-labelledby="createContentModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="createContentModalLabel">Nuevo Material de Estudio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('teacher.contents.store') }}" method="POST" enctype="multipart/form-data" id="createContentForm">
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                    <input type="hidden" name="content_id" id="content-id" value="">
                    <input type="hidden" name="module_id" id="content-modal-module-id" value="">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="content-title" class="form-label fw-bold">Título</label>
                            <input type="text" name="title" id="content-title" class="form-control" required placeholder="Ej. Lectura introductoria">
                        </div>
                        <div class="form-group mb-3">
                            <label for="content-description" class="form-label">Descripción</label>
                            <textarea name="description" id="content-description" class="form-control" rows="3" placeholder="Describe brevemente el recurso..."></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="content-type" class="form-label">Tipo de contenido</label>
                            <select name="content_type" id="content-type" class="form-control" required>
                                <option value="video">Video / recurso externo</option>
                                <option value="document">Documento adjunto</option>
                                <option value="text">Texto / lectura</option>
                                <option value="link">Enlace externo</option>
                            </select>
                        </div>
                        <div class="form-group mb-3" id="content-video-url-group">
                            <label for="content-video-url" class="form-label">URL del recurso o video</label>
                            <input type="url" name="video_url" id="content-video-url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                            <small class="form-text text-muted">Se usará para videos, recursos externos o enlaces embebidos.</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="content-attachment" class="form-label">Archivo adjunto</label>
                            <input type="file" name="attachment" id="content-attachment" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.zip">
                            <small class="form-text text-muted">Máx. 5 MB. Tipos permitidos: PDF, DOC, DOCX, TXT, PPT, PPTX, JPG, PNG, ZIP.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Contenido</button>
                    </div>
                </form>
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
                    <input type="hidden" name="module_id" id="assessment-modal-module-id" value="">
                    <div class="modal-body">
                        <div id="assessment-blocked-warning" class="alert alert-warning" style="display:none;">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Esta evaluación ya tiene intentos registrados. Solo puedes ver la información, no modificarla.
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
                        <button type="submit" class="btn btn-primary" id="assessment-submit-btn">Crear Evaluación</button>
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
                    <input type="hidden" name="module_id" id="task-modal-module-id" value="">
                    <div class="modal-body">
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
        function toggleContentTypeFields(selectedType) {
            const videoGroup = document.getElementById('content-video-url-group');
            if (videoGroup) {
                videoGroup.style.display = selectedType === 'video' ? 'block' : 'none';
            }
        }

        function openContentModal(moduleId, contentData = null) {
            const form = document.getElementById('createContentForm');
            const hidden = document.getElementById('content-modal-module-id');
            const contentId = document.getElementById('content-id');
            if (form) {
                form.action = '{{ route('teacher.contents.store') }}';
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();
                if (contentData) {
                    form.action = '{{ route('teacher.contents.store') }}'.replace('/contents', '/contents/' + contentData.id);
                    let methodInputUpdate = form.querySelector('input[name="_method"]');
                    if (!methodInputUpdate) {
                        methodInputUpdate = document.createElement('input');
                        methodInputUpdate.type = 'hidden';
                        methodInputUpdate.name = '_method';
                        form.appendChild(methodInputUpdate);
                    }
                    methodInputUpdate.value = 'PUT';
                    if (contentId) contentId.value = contentData.id || '';
                    document.getElementById('content-title').value = contentData.title || '';
                    document.getElementById('content-description').value = contentData.description || '';
                    const contentTypeSelect = document.getElementById('content-type');
                    if (contentTypeSelect) {
                        contentTypeSelect.value = contentData.type || 'video';
                    }
                    const contentVideoUrlInput = document.getElementById('content-video-url');
                    if (contentVideoUrlInput) {
                        contentVideoUrlInput.value = contentData.video_url || '';
                    }
                } else {
                    if (contentId) contentId.value = '';
                    form.reset();
                }
                const contentTypeSelect = document.getElementById('content-type');
                if (contentTypeSelect) {
                    toggleContentTypeFields(contentTypeSelect.value);
                }
            }
            if (hidden) hidden.value = moduleId || '';
            $('#createContentModal').modal('show');
        }

        function openAssessmentModal(moduleId) {
            const select = document.getElementById('assessment-module');
            const hidden = document.getElementById('assessment-modal-module-id');
            if (select) {
                select.value = moduleId || '';
            }
            if (hidden) {
                hidden.value = moduleId || '';
            }
            if (select) {
                select.removeAttribute('required');
            }
            $('#createAssessmentModal').modal('show');
        }

        function openTaskModal(moduleId) {
            const select = document.getElementById('task-module');
            const hidden = document.getElementById('task-modal-module-id');
            if (select) {
                select.value = moduleId || '';
            }
            if (hidden) {
                hidden.value = moduleId || '';
            }
            $('#createTaskModal').modal('show');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const assessmentSelect = document.getElementById('assessment-module');
            const assessmentHidden = document.getElementById('assessment-modal-module-id');
            const taskSelect = document.getElementById('task-module');
            const taskHidden = document.getElementById('task-modal-module-id');
            const startDateInput = document.getElementById('assessment-start-date');
            const endDateInput = document.getElementById('assessment-end-date');
            const timeLimitInput = document.getElementById('assessment-time-limit');
            const attemptsInput = document.getElementById('assessment-allowed-attempts');
            const assessmentSubmitBtn = document.getElementById('assessment-submit-btn');
            const assessmentBlockedWarning = document.getElementById('assessment-blocked-warning');
            const assessmentFields = [
                document.getElementById('assessment-title'),
                document.getElementById('assessment-description'),
                document.getElementById('assessment-start-date'),
                document.getElementById('assessment-end-date'),
                document.getElementById('assessment-allowed-attempts'),
                document.getElementById('assessment-time-limit'),
                document.getElementById('assessment-active')
            ].filter(Boolean);
            const taskAttachmentInput = document.getElementById('task-attachment');
            const contentAttachmentInput = document.getElementById('content-attachment');
            const contentTypeSelect = document.getElementById('content-type');
            const taskForm = document.getElementById('createTaskForm');

            const setAssessmentFormLocked = function(locked) {
                assessmentFields.forEach(function(field) {
                    field.disabled = locked;
                });
                if (assessmentSubmitBtn) {
                    assessmentSubmitBtn.disabled = locked;
                    assessmentSubmitBtn.textContent = locked ? 'Bloqueado' : 'Crear Evaluación';
                }
                if (assessmentBlockedWarning) {
                    assessmentBlockedWarning.style.display = locked ? 'block' : 'none';
                }
            };

            const getLocalToday = function() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            const todayDate = getLocalToday();
            if (assessmentSelect && assessmentHidden) {
                assessmentSelect.addEventListener('change', function() {
                    assessmentHidden.value = this.value || '';
                });
            }

            if (taskSelect && taskHidden) {
                taskSelect.addEventListener('change', function() {
                    taskHidden.value = this.value || '';
                });
            }

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

            if (contentTypeSelect) {
                contentTypeSelect.addEventListener('change', function() {
                    toggleContentTypeFields(this.value);
                });
            }
            if (contentAttachmentInput) {
                contentAttachmentInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (!file) return;
                    const maxSizeMB = 5;
                    if (file.size > maxSizeMB * 1024 * 1024) {
                        Swal.fire({ icon: 'error', title: 'Archivo demasiado grande', text: 'El archivo supera el tamaño máximo de 5 MB.' });
                        this.value = '';
                    }
                });
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

            document.querySelectorAll('.edit-content-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.getAttribute('data-content'));
                    openContentModal(data.module_id || '', data);
                });
            });

            document.querySelectorAll('button[data-target="#createContentModal"]').forEach(function(b){
                b.addEventListener('click', function(){
                    openContentModal('');
                });
            });

            // Edit assessment via modal
            document.querySelectorAll('.edit-assessment-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.getAttribute('data-assessment'));
                    const form = document.getElementById('createAssessmentForm');
                    form.action = '{{ route('teacher.assessments.store') }}'.replace('/assessments', '/assessments/' + data.id);
                    const hasAttempts = Boolean(data.has_attempts);
                    setAssessmentFormLocked(hasAttempts);
                    // set method PUT
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';
                    document.getElementById('assessment-module').value = data.module_id || '';
                    document.getElementById('assessment-modal-module-id').value = data.module_id || '';
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
                    document.getElementById('assessment-module').value = '';
                    document.getElementById('assessment-modal-module-id').value = '';
                    form.reset();
                    setAssessmentFormLocked(false);
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
                    document.getElementById('task-module').value = data.module_id || '';
                    document.getElementById('task-modal-module-id').value = data.module_id || '';
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
                    document.getElementById('task-module').value = '';
                    document.getElementById('task-modal-module-id').value = '';
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
