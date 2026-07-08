@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <h1 class="h3 mb-0 text-gray-800">{{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}</h1>
                    @if($isFinished ?? false)
                        <span class="badge bg-info text-dark">Finalizada</span>
                    @endif
                </div>
                <p class="text-muted mb-0">Capacitación con acceso a evaluaciones, tareas, asistencia, calificaciones y anuncios.</p>
            </div>
            <div class="text-end">
                @if(($isFinished ?? false) && (($generalAverage ?? 0) >= 13 || ($averageGrade ?? 0) >= 13))
                    <a href="{{ route('student.courses.certificate.preview', ['id' => $training->training_id]) }}" class="btn btn-success btn-sm me-2">
                        <i class="bi bi-award me-1"></i>Ver Certificado
                    </a>
                @endif
                <a href="{{ route('student.courses.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left-circle me-1"></i>Volver al Curso
                </a>
            </div>
        </div>

        @if($isFinished ?? false)
            <div class="alert alert-info border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>
                        <strong>Esta capacitación finalizó el {{ optional($training->end_date)->format('d/m/Y') ?? 'sin fecha' }}.</strong>
                        Puedes consultar tus resultados, pero no realizar nuevas actividades ni entregas.
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=inicio"
                            class="nav-link @if(request('tab', 'inicio') === 'inicio') active @endif" id="inicio-tab"
                            role="tab">
                            <i class="bi bi-house-fill me-2"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=estudiantes"
                            class="nav-link @if(request('tab') === 'estudiantes') active @endif" id="estudiantes-tab"
                            role="tab">
                            <i class="bi bi-people-fill me-2"></i>Estudiantes
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=asistencias"
                            class="nav-link @if(request('tab') === 'asistencias') active @endif" id="asistencias-tab"
                            role="tab">
                            <i class="bi bi-clipboard-check me-2"></i>Asistencias
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=contenido"
                            class="nav-link @if(request('tab') === 'contenido') active @endif" id="contenido-tab"
                            role="tab">
                            <i class="bi bi-book-fill me-2"></i>Evaluaciones
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=tareas"
                            class="nav-link @if(request('tab') === 'tareas') active @endif" id="tareas-tab"
                            role="tab">
                            <i class="bi bi-list-task me-2"></i>Tareas
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=calificaciones"
                            class="nav-link @if(request('tab') === 'calificaciones') active @endif" id="calificaciones-tab"
                            role="tab">
                            <i class="bi bi-check-circle-fill me-2"></i>Calificaciones
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('student.courses.show', $training->training_id) }}?tab=anuncios"
                            class="nav-link @if(request('tab') === 'anuncios') active @endif" id="anuncios-tab"
                            role="tab">
                            <i class="bi bi-megaphone-fill me-2"></i>Anuncios
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                @if(request('tab', 'inicio') === 'inicio')
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card border-start border-primary border-3 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold text-dark mb-2">{{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}</h5>
                                    <p class="text-muted small mb-2">Instructor: {{ $training->teacher->person->first_names ?? $training->teacher->name ?? 'Sin profesor' }}</p>
                                    <p class="text-muted small mb-0">{{ $training->course->description ?? 'Sin descripción del curso.' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-start border-success border-3 shadow-sm h-100">
                                <div class="card-body">
                                    <p class="text-muted small mb-2">Inicio: <strong>{{ optional($training->start_date)->format('d/m/Y') ?? 'Sin fecha' }}</strong></p>
                                    <p class="text-muted small mb-2">Fin: <strong>{{ optional($training->end_date)->format('d/m/Y') ?? 'Sin fecha' }}</strong></p>
                                    <p class="text-muted small mb-2">Modalidad: <strong>{{ ucfirst($training->modality ?? 'No definida') }}</strong></p>
                                    <p class="text-muted small mb-0">Evaluaciones: <strong>{{ $training->assessments->count() }}</strong> · Tareas: <strong>{{ $training->tasks->count() }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 fw-bold">Módulos y contenidos</h5>
                        </div>
                        <div class="card-body">
                            @if($modules->isNotEmpty())
                                <div class="accordion" id="studentModulesAccordion">
                                    @foreach($modules as $index => $module)
                                        <div class="card mb-3 border-0 shadow-sm">
                                            <div class="card-header bg-light p-3" id="studentModuleHeading{{ $module->module_id ?? $module->id }}" data-toggle="collapse" data-target="#studentModuleCollapse{{ $module->module_id ?? $module->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="studentModuleCollapse{{ $module->module_id ?? $module->id }}" role="button" style="cursor:pointer;">
                                                <div class="d-flex justify-content-between align-items-center gap-3">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-dark">{{ $module->title }}</h6>
                                                        <p class="text-muted small mb-0">{{ $module->description ?: 'Explora los contenidos de este módulo.' }}</p>
                                                    </div>
                                                    <span class="badge bg-light text-dark">{{ $module->contents->count() }} contenidos</span>
                                                </div>
                                            </div>

                                            <div id="studentModuleCollapse{{ $module->module_id ?? $module->id }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="studentModuleHeading{{ $module->module_id ?? $module->id }}" data-parent="#studentModulesAccordion">
                                                <div class="card-body p-3">
                                                    @if($module->contents->isNotEmpty())
                                                        <div class="list-group">
                                                            @foreach($module->contents as $contentItem)
                                                                @php $isCompleted = in_array($contentItem->content_id, $completedContentIds ?? [], true); @endphp
                                                                <a href="{{ route('student.courses.view-content', ['training' => $training->training_id, 'content' => $contentItem->content_id]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                    <span>
                                                                        <i class="me-2 {{ $isCompleted ? 'bi bi-check-circle-fill text-success' : 'bi bi-circle text-secondary' }}"></i>
                                                                        {{ $contentItem->title }}
                                                                    </span>
                                                                    <small class="text-muted text-uppercase">{{ $contentItem->type }}</small>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="alert alert-info small mb-0" role="alert">
                                                            <i class="bi bi-info-circle me-2"></i>No hay contenidos registrados para este módulo todavía.
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info mb-0" role="alert">
                                    <i class="bi bi-info-circle me-2"></i>No hay módulos disponibles para este curso.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body text-center">
                                    <i class="bi bi-book-half text-primary h4 mb-2"></i>
                                    <h5 class="h6 fw-bold mb-1">Evaluaciones</h5>
                                    <p class="text-muted mb-0">{{ $training->assessments->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body text-center">
                                    <i class="bi bi-clock-history text-info h4 mb-2"></i>
                                    <h5 class="h6 fw-bold mb-1">Asistencias</h5>
                                    <p class="text-muted mb-0">{{ $attendances->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body text-center">
                                    <i class="bi bi-megaphone text-success h4 mb-2"></i>
                                    <h5 class="h6 fw-bold mb-1">Anuncios</h5>
                                    <p class="text-muted mb-0">{{ $announcements->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(request('tab') === 'estudiantes')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Estudiantes Matriculados</h5>
                    </div>
                    @if($training->enrollments->isNotEmpty())
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
                                    @foreach($training->enrollments as $enrollmentItem)
                                        @php $isActive = strtoupper((string) ($enrollmentItem->status ?? '')) === 'A'; @endphp
                                        <tr>
                                            <td class="fw-bold">{{ optional($enrollmentItem->student->person)->first_names ?? 'Sin nombre' }} {{ optional($enrollmentItem->student->person)->last_names ?? '' }}</td>
                                            <td>{{ optional($enrollmentItem->student->person)->document_number ?? '-' }}</td>
                                            <td><small>{{ optional($enrollmentItem->student->person)->email ?? '-' }}</small></td>
                                            <td><small>{{ optional($enrollmentItem->student->person)->phone ?? '-' }}</small></td>
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
                    @else
                        <div class="alert alert-info mb-0" role="alert">
                            <i class="bi bi-info-circle me-2"></i>No hay otros estudiantes registrados en este curso.
                        </div>
                    @endif

                @elseif(request('tab') === 'asistencias')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Historial de asistencia</h5>
                    </div>
                    @if($attendances->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Sesión</th>
                                        <th>Asistencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($attendances as $attendance)
                                        <tr>
                                            <td>{{ optional($attendance->schedule->date)->format('d/m/Y') ?? 'Sin fecha' }}</td>
                                            <td>{{ $attendance->schedule->topic ?? 'Sesión' }}</td>
                                            <td>
                                                @php $status = $attendance->attendance_status ?? $attendance->attendance; @endphp
                                                @if($status === 'present')
                                                    <span class="badge bg-success">Presente</span>
                                                @elseif($status === 'absent')
                                                    <span class="badge bg-danger">Ausente</span>
                                                @elseif($status === 'justified')
                                                    <span class="badge bg-info text-dark">Justificado</span>
                                                @elseif($status === 'late')
                                                    <span class="badge bg-warning text-dark">Tardanza</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($status ?? 'Desconocido') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0" role="alert">
                            <i class="bi bi-info-circle me-2"></i>No hay registros de asistencia para ti en este curso.
                        </div>
                    @endif

                @elseif(request('tab', 'contenido') === 'contenido')
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-bold">Evaluaciones por módulo</h6>
                        </div>
                        <div class="card-body">
                            @if(($modules ?? collect())->isEmpty())
                                <div class="alert alert-info mb-0" role="alert">
                                    <i class="bi bi-inbox me-2"></i>No hay evaluaciones disponibles.
                                </div>
                            @else
                                <div class="accordion" id="studentAssessmentsAccordion">
                                    @foreach($modules as $index => $module)
                                        @php
                                            $moduleAssessments = $module->assessments ?? collect();
                                            $moduleAttemptRecords = $attempts ? $attempts->whereIn('assessment_id', $moduleAssessments->pluck('assessment_id')->filter()->all()) : collect();
                                            $moduleCompletedCount = $moduleAttemptRecords->whereNotNull('submitted_at')->count();
                                            $moduleStatus = $moduleCompletedCount > 0 && $moduleCompletedCount >= $moduleAssessments->count() ? 'Completado' : ($moduleCompletedCount > 0 ? 'En curso' : 'Pendiente');
                                            $moduleStatusClass = $moduleStatus === 'Completado' ? 'secondary' : ($moduleStatus === 'En curso' ? 'success' : 'info');
                                            $moduleBorderColor = $moduleStatus === 'Completado' ? '#6c757d' : ($moduleStatus === 'En curso' ? '#28a745' : '#17a2b8');
                                        @endphp

                                        <div class="card mb-3" style="border-left: 4px solid {{ $moduleBorderColor }}; transition: all 0.2s ease-in-out;" onmouseover="this.style.boxShadow='0 0.5rem 1rem rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow='none'">
                                            <div class="card-header bg-white p-3" id="studentAssessmentHeading{{ $module->id }}" data-toggle="collapse" data-target="#studentAssessmentCollapse{{ $module->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="studentAssessmentCollapse{{ $module->id }}" role="button" style="cursor:pointer;">
                                                <div class="d-flex justify-content-between align-items-center gap-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-journal-check me-3 text-primary"></i>
                                                        <div>
                                                            <h6 class="mb-1 fw-bold text-dark">Módulo {{ $module->order }}: {{ $module->title }}</h6>
                                                            <small class="text-muted">
                                                                <span class="badge badge-{{ $moduleStatusClass }} me-2">{{ $moduleStatus }}</span>
                                                                {{ $moduleAssessments->count() }} evaluación(es)
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <i class="bi bi-chevron-{{ $index === 0 ? 'up' : 'down' }}"></i>
                                                </div>
                                            </div>
                                            <div id="studentAssessmentCollapse{{ $module->id }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="studentAssessmentHeading{{ $module->id }}" data-parent="#studentAssessmentsAccordion">
                                                <div class="card-body p-4">
                                                    @if($moduleAssessments->isEmpty())
                                                        <div class="text-center py-3 border rounded bg-light">
                                                            <i class="bi bi-journal-x h3 d-block text-muted mb-2"></i>
                                                            <p class="text-muted mb-0">No hay evaluaciones en este módulo</p>
                                                        </div>
                                                    @else
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
                                                                    @foreach($moduleAssessments as $assessment)
                                                                        @php
                                                                            $assessmentAttempts = $attempts ? $attempts->where('assessment_id', $assessment->assessment_id) : collect();
                                                                            $pendingAttempt = $assessmentAttempts->whereNull('submitted_at')->first();
                                                                            $submittedAttempts = $assessmentAttempts->whereNotNull('submitted_at');
                                                                            $bestScore = $submittedAttempts->max('score');
                                                                            $attemptsUsed = $assessmentAttempts->count();
                                                                            $remainingAttempts = max(0, $assessment->allowed_attempts - $attemptsUsed);
                                                                            $assessmentAvailable = $assessment->active
                                                                                && (! $assessment->start_date || \Carbon\Carbon::today()->gte(\Carbon\Carbon::parse($assessment->start_date)->startOfDay()))
                                                                                && (! $assessment->end_date || \Carbon\Carbon::today()->lte(\Carbon\Carbon::parse($assessment->end_date)->endOfDay()))
                                                                                && ! $assessment->training->isClosed();
                                                                        @endphp
                                                                        <tr>
                                                                            <td>
                                                                                <div class="fw-bold">{{ $assessment->title }}</div>
                                                                                @if(! empty($assessment->description))
                                                                                    <small class="text-muted d-block">{{ Str::limit($assessment->description, 70) }}</small>
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $assessment->end_date ? \Carbon\Carbon::parse($assessment->end_date)->format('d/m/Y') : 'Sin fecha' }}</td>
                                                                            <td>
                                                                                @if($pendingAttempt)
                                                                                    <span class="badge bg-info text-dark">En curso</span>
                                                                                @elseif($submittedAttempts->isNotEmpty())
                                                                                    <span class="badge bg-success">Completada</span>
                                                                                @elseif($assessmentAvailable)
                                                                                    <span class="badge bg-primary">Disponible</span>
                                                                                @else
                                                                                    <span class="badge bg-danger">No disponible</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if($pendingAttempt)
                                                                                    <a href="{{ route('student.assessment.take', $assessment->assessment_id) }}?start=1" class="btn btn-sm btn-primary">
                                                                                        <i class="bi bi-play-circle me-1"></i>Continuar evaluación
                                                                                    </a>
                                                                                @elseif($submittedAttempts->isNotEmpty())
                                                                                    <div class="d-flex flex-column align-items-start gap-2">
                                                                                        <a href="{{ route('student.assessment.results', $assessment->assessment_id) }}" class="btn btn-sm btn-outline-success">
                                                                                            <i class="bi bi-eye me-1"></i>Ver resultados
                                                                                        </a>
                                                                                        <span class="small text-muted">Mejor puntaje: {{ $bestScore !== null ? $bestScore : 0 }}</span>
                                                                                    </div>
                                                                                @elseif($assessmentAvailable && $remainingAttempts > 0 && ! ($isFinished ?? false))
                                                                                    <a href="{{ route('student.assessment.take', $assessment->assessment_id) }}?start=1" class="btn btn-sm btn-primary">
                                                                                        <i class="bi bi-play-circle me-1"></i>Iniciar evaluación
                                                                                    </a>
                                                                                @else
                                                                                    <button type="button" class="btn btn-sm btn-secondary" disabled data-toggle="tooltip" title="{{ ($isFinished ?? false) ? 'No puedes realizar nuevas actividades en una capacitación finalizada.' : 'No disponible' }}">
                                                                                        <i class="bi bi-lock me-1"></i>{{ ($isFinished ?? false) ? 'Consulta' : 'No disponible' }}
                                                                                    </button>
                                                                                @endif
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
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif(request('tab') === 'tareas')
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light py-3">
                            <h6 class="mb-0 fw-bold">Tareas por módulo</h6>
                        </div>
                        <div class="card-body">
                            @if(($modules ?? collect())->isEmpty())
                                <div class="alert alert-info mb-0" role="alert">
                                    <i class="bi bi-inbox me-2"></i>No hay tareas registradas.
                                </div>
                            @else
                                <div class="accordion" id="studentTasksAccordion">
                                    @foreach($modules as $index => $module)
                                        @php
                                            $moduleTasks = $module->tasks ?? collect();
                                            $moduleSubmissions = $submissions ? $submissions->whereIn('task_id', $moduleTasks->pluck('task_id')->filter()->all()) : collect();
                                            $moduleCompletedCount = $moduleSubmissions->whereNotNull('submitted_at')->count();
                                            $moduleStatus = $moduleCompletedCount > 0 && $moduleCompletedCount >= $moduleTasks->count() ? 'Completado' : ($moduleCompletedCount > 0 ? 'En curso' : 'Pendiente');
                                            $moduleStatusClass = $moduleStatus === 'Completado' ? 'secondary' : ($moduleStatus === 'En curso' ? 'success' : 'info');
                                            $moduleBorderColor = $moduleStatus === 'Completado' ? '#6c757d' : ($moduleStatus === 'En curso' ? '#28a745' : '#17a2b8');
                                        @endphp

                                        <div class="card mb-3" style="border-left: 4px solid {{ $moduleBorderColor }}; transition: all 0.2s ease-in-out;" onmouseover="this.style.boxShadow='0 0.5rem 1rem rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow='none'">
                                            <div class="card-header bg-white p-3" id="studentTaskHeading{{ $module->id }}" data-toggle="collapse" data-target="#studentTaskCollapse{{ $module->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="studentTaskCollapse{{ $module->id }}" role="button" style="cursor:pointer;">
                                                <div class="d-flex justify-content-between align-items-center gap-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-journal-text me-3 text-primary"></i>
                                                        <div>
                                                            <h6 class="mb-1 fw-bold text-dark">Módulo {{ $module->order }}: {{ $module->title }}</h6>
                                                            <small class="text-muted">
                                                                <span class="badge badge-{{ $moduleStatusClass }} me-2">{{ $moduleStatus }}</span>
                                                                {{ $moduleTasks->count() }} tarea(s)
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <i class="bi bi-chevron-{{ $index === 0 ? 'up' : 'down' }}"></i>
                                                </div>
                                            </div>
                                            <div id="studentTaskCollapse{{ $module->id }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="studentTaskHeading{{ $module->id }}" data-parent="#studentTasksAccordion">
                                                <div class="card-body p-4">
                                                    @if($moduleTasks->isEmpty())
                                                        <div class="text-center py-3 border rounded bg-light">
                                                            <i class="bi bi-journal-x h3 d-block text-muted mb-2"></i>
                                                            <p class="text-muted mb-0">No hay tareas en este módulo</p>
                                                        </div>
                                                    @else
                                                        <div class="table-responsive">
                                                            <table class="table table-hover mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Título</th>
                                                                        <th>Fecha límite</th>
                                                                        <th>Estado</th>
                                                                        <th>Calificación</th>
                                                                        <th>Acciones</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($moduleTasks as $task)
                                                                        @php
                                                                            $submission = $moduleSubmissions->where('task_id', $task->task_id)->sortByDesc('submitted_at')->first();
                                                                            $isLate = $task->due_date && $task->due_date->lt(now()) && ! $submission;
                                                                            $taskState = 'Pendiente';
                                                                            $taskBadgeClass = 'warning';

                                                                            if ($submission && $submission->grade !== null) {
                                                                                $taskState = 'Calificada';
                                                                                $taskBadgeClass = 'info';
                                                                            } elseif ($submission) {
                                                                                $taskState = 'Entregada';
                                                                                $taskBadgeClass = 'success';
                                                                            } elseif ($isLate) {
                                                                                $taskState = 'Vencida';
                                                                                $taskBadgeClass = 'danger';
                                                                            }
                                                                        @endphp
                                                                        <tr>
                                                                            <td>
                                                                                <div class="fw-bold">{{ $task->title }}</div>
                                                                                @if(! empty($task->description))
                                                                                    <small class="text-muted d-block">{{ Str::limit($task->description, 70) }}</small>
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'Sin fecha' }}</td>
                                                                            <td>
                                                                                <span class="badge bg-{{ $taskBadgeClass }} text-dark">{{ $taskState }}</span>
                                                                            </td>
                                                                            <td>
                                                                                @if($submission && $submission->grade !== null)
                                                                                    <span class="badge bg-primary">{{ $submission->grade }}</span>
                                                                                @else
                                                                                    <span class="text-muted">-</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                <div class="d-flex flex-column align-items-start gap-2">
                                                                                    @if($submission)
                                                                                        <a href="{{ asset('storage/'.$submission->file_path) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                                                                            <i class="bi bi-eye me-1"></i>Ver entrega
                                                                                        </a>
                                                                                    @elseif(! ($isFinished ?? false))
                                                                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#taskSubmissionModal{{ $task->task_id }}">
                                                                                            <i class="bi bi-upload me-1"></i>Entregar tarea
                                                                                        </button>
                                                                                    @else
                                                                                        <button type="button" class="btn btn-sm btn-secondary" disabled data-toggle="tooltip" title="No puedes realizar nuevas entregas en una capacitación finalizada.">
                                                                                            <i class="bi bi-lock me-1"></i>Consulta
                                                                                        </button>
                                                                                    @endif
                                                                                </div>
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
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    @foreach($modules as $module)
                        @foreach($module->tasks ?? collect() as $task)
                            @php $submission = $submissions ? $submissions->where('task_id', $task->task_id)->sortByDesc('submitted_at')->first() : null; @endphp
                            <div class="modal fade" id="taskSubmissionModal{{ $task->task_id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Entregar: {{ $task->title }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('student.tasks.submit', $task->task_id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group mb-3">
                                                    <label class="form-label small fw-semibold">Comentarios de la entrega</label>
                                                    <textarea name="submission_text" rows="3" class="form-control">{{ old('submission_text', $submission->submission_text ?? '') }}</textarea>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label small fw-semibold">Archivo adjunto (opcional)</label>
                                                    <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Guardar entrega</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach

                @elseif(request('tab') === 'calificaciones')
                    @php
                        $generalPercent = $generalAverage > 0 ? min(100, round(($generalAverage / 20) * 100)) : 0;
                        $generalState = $generalAverage >= 14 ? 'Aprobado' : ($generalAverage >= 11 ? 'En Progreso' : 'Desaprobado');
                        $generalBadgeClass = $generalAverage >= 14 ? 'success' : ($generalAverage >= 11 ? 'warning' : 'danger');
                        $generalBarColor = $generalAverage >= 14 ? '#28a745' : ($generalAverage >= 11 ? '#fd7e14' : '#dc3545');
                        $hasAnyGrades = false;
                        foreach ($moduleReports as $report) {
                            foreach ($report['items'] as $item) {
                                if (! is_null($item['grade'])) {
                                    $hasAnyGrades = true;
                                    break;
                                }
                            }
                            if ($hasAnyGrades) {
                                break;
                            }
                        }
                    @endphp

                    <div class="mb-4">
                        <div class="card border-0 shadow-sm rounded-lg overflow-hidden" style="background: linear-gradient(135deg, #f8f9fc 0%, #eef4fb 100%); border-left: 4px solid #4e73df;">
                            <div class="card-body p-4">
                                <div class="row align-items-center g-3">
                                    <div class="col-12 col-lg-2 text-center">
                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background: rgba(78, 115, 223, 0.12);">
                                            <i class="bi bi-graph-up text-primary h3 mb-0"></i>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-7">
                                        <h5 class="fw-bold text-dark mb-2">Mi Progreso General</h5>
                                        <p class="text-muted mb-3">Tu avance acumulado en evaluaciones y tareas del curso.</p>
                                        <div class="progress rounded-pill" style="height: 10px; background: #e9ecef;">
                                            <div class="progress-bar progress-bar-animated" role="progressbar" aria-valuenow="{{ $generalPercent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $generalPercent }}%; background: {{ $generalBarColor }};"></div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3 text-lg-end">
                                        <div class="display-4 fw-bold text-dark mb-1">{{ number_format($generalAverage, 1) }}</div>
                                        <div class="small text-muted mb-2">/ 20 puntos</div>
                                        <span class="badge badge-{{ $generalBadgeClass }} px-3 py-2">{{ $generalState }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!empty($moduleReports))
                        <div class="row g-4">
                            @foreach($moduleReports as $report)
                                @php
                                    $moduleAverage = $report['average'];
                                    $moduleStatus = is_null($moduleAverage) ? 'pending' : ($moduleAverage >= 14 ? 'completed' : ($moduleAverage >= 11 ? 'in-progress' : 'pending'));
                                    $moduleBorderColor = $moduleStatus === 'completed' ? '#28a745' : ($moduleStatus === 'in-progress' ? '#17a2b8' : '#6c757d');
                                    $moduleValueClass = is_null($moduleAverage) ? 'text-muted' : ($moduleAverage >= 16 ? 'grade-value-excellent' : ($moduleAverage >= 14 ? 'grade-value-good' : ($moduleAverage >= 11 ? 'grade-value-regular' : 'grade-value-fail')));
                                    $modulePercent = is_null($moduleAverage) ? 0 : min(100, round(($moduleAverage / 20) * 100));
                                    $moduleBadgeClass = $moduleStatus === 'completed' ? 'success' : ($moduleStatus === 'in-progress' ? 'info' : 'secondary');
                                    $moduleLabel = $moduleStatus === 'completed' ? 'Completado' : ($moduleStatus === 'in-progress' ? 'En curso' : 'Pendiente');
                                    $activities = $report['items'] ?? [];
                                    $visibleActivities = array_slice($activities, 0, 5);
                                    $hiddenActivities = array_slice($activities, 5);
                                @endphp

                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card module-grade-card {{ $moduleStatus }} h-100">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: rgba(78, 115, 223, 0.12);">
                                                        <i class="bi bi-journals text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-dark mb-0">{{ $report['module']->title }}</h6>
                                                        <small class="text-muted">{{ count($activities) }} actividad(es)</small>
                                                    </div>
                                                </div>
                                                <span class="badge badge-{{ $moduleBadgeClass }}">{{ $moduleLabel }}</span>
                                            </div>

                                            <div class="rounded p-3 mb-3" style="background: #f8f9fc; border: 1px solid #eef2f7;">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="small text-muted">Promedio del módulo</span>
                                                    <i class="bi bi-calculator text-primary"></i>
                                                </div>
                                                <div class="fw-bold {{ $moduleValueClass }}" style="font-size: 1.2rem;">
                                                    {{ is_null($moduleAverage) ? 'N/A' : number_format($moduleAverage, 1).' / 20' }}
                                                </div>
                                            </div>

                                            <div class="progress rounded-pill mb-3" style="height: 8px; background: #e9ecef;">
                                                <div class="progress-bar progress-bar-animated" role="progressbar" aria-valuenow="{{ $modulePercent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $modulePercent }}%; background: {{ $moduleBorderColor }};"></div>
                                            </div>

                                            @if(! empty($activities))
                                                <div class="border-top pt-3">
                                                    <div class="small text-muted mb-2 fw-semibold">Actividades recientes</div>
                                                    <div class="d-flex flex-column gap-2">
                                                        @foreach($visibleActivities as $item)
                                                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <i class="bi {{ $item['type'] === 'task' ? 'bi-list-task' : 'bi-journal-check' }} text-primary"></i>
                                                                    <div>
                                                                        <div class="small fw-semibold text-dark">{{ Str::limit($item['title'], 28) }}</div>
                                                                        <div class="small text-muted">{{ $item['type'] === 'task' ? 'Tarea' : 'Evaluación' }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-end">
                                                                    @if(! is_null($item['grade']))
                                                                        <span class="badge badge-success">{{ $item['grade'] }}</span>
                                                                    @else
                                                                        <span class="badge badge-secondary">Pendiente</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @if(! empty($hiddenActivities))
                                                        <div class="mt-3">
                                                            <a class="small text-primary fw-semibold" data-toggle="collapse" href="#module-activities-{{ $loop->index }}" role="button" aria-expanded="false" aria-controls="module-activities-{{ $loop->index }}">
                                                                <i class="bi bi-chevron-down me-1"></i>Ver más
                                                            </a>
                                                            <div class="collapse mt-2" id="module-activities-{{ $loop->index }}">
                                                                @foreach($hiddenActivities as $item)
                                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <i class="bi {{ $item['type'] === 'task' ? 'bi-list-task' : 'bi-journal-check' }} text-primary"></i>
                                                                            <div>
                                                                                <div class="small fw-semibold text-dark">{{ Str::limit($item['title'], 28) }}</div>
                                                                                <div class="small text-muted">{{ $item['type'] === 'task' ? 'Tarea' : 'Evaluación' }}</div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-end">
                                                                            @if(! is_null($item['grade']))
                                                                                <span class="badge badge-success">{{ $item['grade'] }}</span>
                                                                            @else
                                                                                <span class="badge badge-secondary">Pendiente</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-center py-3 border rounded bg-light">
                                                    <i class="bi bi-inbox h4 d-block text-muted mb-2"></i>
                                                    <div class="small text-muted">No hay actividades registradas en este módulo.</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card border-0 shadow-sm rounded-lg p-4 text-center">
                            <div class="mb-3">
                                <i class="bi bi-inbox h1 text-muted"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Aún no tienes calificaciones</h6>
                            <p class="text-muted mb-0">Completa tus evaluaciones y tareas para ver tu progreso aquí.</p>
                        </div>
                    @endif

                @elseif(request('tab') === 'anuncios')
                    @if($announcements->isEmpty())
                        <div class="alert alert-secondary mb-0" role="alert">
                            <i class="bi bi-inbox me-2"></i>No hay anuncios en esta capacitación.
                        </div>
                    @else
                        @foreach($announcements as $announcement)
                            <div class="border rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">Anuncio</h6>
                                        <small class="text-muted">{{ optional($announcement->created_at)->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                                <div class="mb-3 text-break">
                                    {!! nl2br(e($announcement->content)) !!}
                                </div>
                                @if(!empty($announcement->link))
                                    <div class="mb-3">
                                        <a href="{{ $announcement->link }}" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-link-45deg me-1"></i>{{ $announcement->link }}
                                        </a>
                                    </div>
                                @endif
                                @if(!empty($announcement->attachments))
                                    <div class="row g-2">
                                        @foreach(collect($announcement->attachments) as $attachment)
                                            <div class="col-12 col-sm-6 col-xl-4">
                                                <div class="card border">
                                                    <div class="card-body p-2">
                                                        @if(!empty($attachment['path']) && str_contains($attachment['mime'] ?? '', 'image'))
                                                            <a href="{{ asset('storage/'.$attachment['path']) }}" target="_blank">
                                                                <img src="{{ asset('storage/'.$attachment['path']) }}" class="img-fluid rounded mb-2" alt="{{ $attachment['name'] ?? 'Adjunto' }}">
                                                            </a>
                                                        @endif
                                                        <div class="small mb-2">
                                                            <strong>{{ $attachment['name'] ?? 'Archivo adjunto' }}</strong>
                                                        </div>
                                                        @if(!empty($attachment['path']))
                                                            <a href="{{ asset('storage/'.$attachment['path']) }}" target="_blank" class="text-decoration-none small">
                                                                <i class="bi bi-download me-1"></i>Descargar archivo
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
    <style>
        .course-banner-image { background-position: center center; background-size: cover; width: 100%; }
        .course-banner-fallback { background: #0d6efd; }
        .course-banner-overlay { position: absolute; inset: 0; pointer-events: none; }

        .module-grade-card {
            border: none;
            border-left: 4px solid #4e73df;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            background: #fff;
        }

        .module-grade-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .module-grade-card.completed { border-left-color: #28a745; }
        .module-grade-card.in-progress { border-left-color: #17a2b8; }
        .module-grade-card.pending { border-left-color: #6c757d; }

        .progress-bar-animated {
            animation: progress-bar-stripes 1s linear infinite;
        }

        .grade-value-excellent { color: #1e7e34; font-weight: 700; }
        .grade-value-good { color: #28a745; font-weight: 600; }
        .grade-value-regular { color: #fd7e14; font-weight: 600; }
        .grade-value-fail { color: #dc3545; font-weight: 700; }

        @keyframes progress-bar-stripes {
            from { background-position: 1rem 0; }
            to { background-position: 0 0; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.exam-start-btn').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const startUrl = button.dataset.startUrl || button.href;

                    Swal.fire({
                        title: '¿Estás listo para iniciar el examen?',
                        text: 'El tiempo comenzará cuando confirmes. Asegúrate de estar preparado.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, comenzar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = startUrl;
                        }
                    });
                });
            });
        });
    </script>
@endsection
