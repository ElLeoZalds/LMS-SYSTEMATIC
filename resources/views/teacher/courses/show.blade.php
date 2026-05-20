@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">{{ $training->course->title }}</h1>
                <small class="text-muted">Código: {{ $training->course->code ?? 'N/A' }} | Modalidad:
                    <strong>{{ ucfirst($training->modality) }}</strong></small>
            </div>
            <a href="{{ route('teacher.courses') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver a Cursos
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-primary h4 mb-2"></i>
                        <h5 class="h6 fw-bold">{{ $totalStudents }}</h5>
                        <small class="text-muted">Estudiantes</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle-fill text-success h4 mb-2"></i>
                        <h5 class="h6 fw-bold">{{ $totalAssessments }}</h5>
                        <small class="text-muted">Tareas/Evaluaciones</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check text-info h4 mb-2"></i>
                        <h5 class="h6 fw-bold">{{ $totalAttendanceRecords }}</h5>
                        <small class="text-muted">Registros de Asistencia</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <span class="badge bg-success p-2 mb-2">Activo</span>
                        <small class="text-muted d-block">Estado del Curso</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header bg-white border-bottom">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=inicio"
                            class="nav-link @if(request('tab', 'inicio') === 'inicio') active @endif" id="inicio-tab"
                            role="tab">
                            <i class="bi bi-house-fill me-2"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=estudiantes"
                            class="nav-link @if(request('tab') === 'estudiantes') active @endif" id="estudiantes-tab"
                            role="tab">
                            <i class="bi bi-people-fill me-2"></i>Estudiantes
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=asistencias"
                            class="nav-link @if(request('tab') === 'asistencias') active @endif" id="asistencias-tab"
                            role="tab">
                            <i class="bi bi-clipboard-check me-2"></i>Asistencias
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=contenido"
                            class="nav-link @if(request('tab') === 'contenido') active @endif" id="contenido-tab"
                            role="tab">
                            <i class="bi bi-book-fill me-2"></i>Contenido/Tareas
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=calificaciones"
                            class="nav-link @if(request('tab') === 'calificaciones') active @endif" id="calificaciones-tab"
                            role="tab">
                            <i class="bi bi-check-circle-fill me-2"></i>Calificaciones
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">

                @if(request('tab', 'inicio') === 'inicio')
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <a href="{{ route('teacher.attendance.create', ['training_id' => $training->training_id]) }}" class="text-decoration-none">
                                <div class="card border-start border-primary border-3 shadow-sm h-100"
                                    style="cursor: pointer; transition: box-shadow 0.3s;">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold text-dark mb-2">
                                            <i class="bi bi-calendar-check text-primary me-2"></i>Registrar Asistencia
                                        </h5>
                                        <p class="card-text text-muted small">Marca la asistencia de tus estudiantes</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=contenido" class="text-decoration-none">
                                <div class="card border-start border-success border-3 shadow-sm h-100"
                                    style="cursor: pointer; transition: box-shadow 0.3s;">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold text-dark mb-2">
                                            <i class="bi bi-plus-circle text-success me-2"></i>Crear Tarea o Evaluación
                                        </h5>
                                        <p class="card-text text-muted small">Asigna una nueva tarea o evaluación en la pestaña de contenidos</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=estudiantes" class="text-decoration-none">
                                <div class="card border-start border-info border-3 shadow-sm h-100"
                                    style="cursor: pointer; transition: box-shadow 0.3s;">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold text-dark mb-2">
                                            <i class="bi bi-people text-info me-2"></i>Ver Estudiantes
                                        </h5>
                                        <p class="card-text text-muted small">Consulta la lista completa de estudiantes</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="card border-start border-warning border-3 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold text-dark mb-3">
                                        <i class="bi bi-info-circle text-warning me-2"></i>Información
                                    </h5>
                                    <div class="small">
                                        <p class="mb-2"><strong>Modalidad:</strong> {{ ucfirst($training->modality) }}</p>
                                        <p class="mb-2"><strong>Estado:</strong> <span class="badge bg-success">Activo</span>
                                        </p>
                                        <p class="mb-0"><strong>Estudiantes:</strong> {{ $totalStudents }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(request('tab') === 'estudiantes')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Estudiantes Matriculados <span
                                class="badge bg-primary">{{ $totalStudents }}</span></h5>
                    </div>

                    @if($training->enrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>DNI</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($training->enrollments as $enrollment)
                                        <tr>
                                            <td class="fw-bold">{{ $enrollment->student->person->first_names }}
                                                {{ $enrollment->student->person->last_names }}</td>
                                            <td>{{ $enrollment->student->person->document_number }}</td>
                                            <td><small>{{ $enrollment->student->person->email }}</small></td>
                                            <td><small>{{ $enrollment->student->person->phone }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center mb-0" role="alert">
                            <i class="bi bi-info-circle me-2"></i>No hay estudiantes matriculados en este curso aún.
                        </div>
                    @endif

                @elseif(request('tab') === 'asistencias')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Registro de Asistencias</h5>
                        <a href="{{ route('teacher.attendance.create', ['training_id' => $training->training_id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-arrow-right me-1"></i>Registrar Asistencia
                        </a>
                    </div>
                    <p class="text-muted">Total de registros: <strong>{{ $totalAttendanceRecords }}</strong></p>

                @elseif(request('tab') === 'contenido')
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-2">Contenido y Tareas</h5>
                            <p class="text-muted small mb-0">
                                Inicio: <strong>{{ $training->start_date ? \Carbon\Carbon::parse($training->start_date)->format('d/m/Y') : 'Sin fecha' }}</strong>
                                · Fin: <strong>{{ $training->end_date ? \Carbon\Carbon::parse($training->end_date)->format('d/m/Y') : 'Sin fecha' }}</strong>
                            </p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createAssessmentModal">
                                <i class="bi bi-plus-lg me-1"></i>Nueva Evaluación
                            </button>
                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#createTaskModal">
                                <i class="bi bi-plus-lg me-1"></i>Nueva Tarea
                            </button>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-lg-8">
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
                                                    <th class="text-center">Inicio</th>
                                                    <th class="text-center">Fin</th>
                                                    <th class="text-center">Intentos</th>
                                                    <th class="text-center">Estado</th>
                                                    <th class="text-end">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($training->assessments as $assessment)
                                                    <tr>
                                                        <td>{{ $assessment->title }}</td>
                                                        <td class="text-center">{{ $assessment->start_date ? \Carbon\Carbon::parse($assessment->start_date)->format('d/m/Y') : 'Sin fecha' }}</td>
                                                        <td class="text-center">{{ $assessment->end_date ? \Carbon\Carbon::parse($assessment->end_date)->format('d/m/Y') : 'Sin fecha' }}</td>
                                                        <td class="text-center">{{ $assessment->allowed_attempts }}</td>
                                                        <td class="text-center">
                                                            <span class="badge @if($assessment->active) bg-success @else bg-secondary @endif">{{ $assessment->active ? 'Activo' : 'Inactivo' }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{ route('teacher.assessments.show', ['training_id' => $training->training_id]) }}" class="btn btn-sm btn-info text-white">
                                                                <i class="bi bi-pencil-square"></i> Gestionar Preguntas
                                                            </a>
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
                        </div>

                        <div class="col-lg-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-light py-3">
                                    <h6 class="mb-0 fw-bold">Tareas creadas</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">Las tareas entregables asignadas se listan a continuación.</p>
                                    <div class="list-group list-group-flush">
                                        @if($training->tasks && $training->tasks->count() > 0)
                                            @foreach($training->tasks as $task)
                                                <div class="list-group-item px-0 py-3">
                                                    <div class="d-flex w-100 justify-content-between mb-1">
                                                        <h6 class="fw-bold text-dark mb-0">{{ $task->title }}</h6>
                                                    </div>
                                                    <p class="text-muted small mb-2 text-truncate" style="max-width: 250px;">{{ $task->description }}</p>
                                                    <small class="text-secondary d-block mb-2">
                                                        <i class="bi bi-calendar-event me-1"></i>Vence: {{ $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'Sin fecha' }}
                                                    </small>
                                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                                        <div>
                                                            <span class="badge bg-light text-dark border" title="Total de entregas">
                                                                <i class="bi bi-file-earmark-arrow-up text-primary me-1"></i>{{ $task->submissions->count() }}
                                                            </span>
                                                            <span class="badge bg-warning text-dark" title="Pendientes de calificar">
                                                                <i class="bi bi-clock-history me-1"></i>{{ $task->submissions->whereNull('grade')->count() }} por revisar
                                                            </span>
                                                        </div>
                                                        <a href="{{ route('teacher.tasks.submissions', $task->task_id) }}" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size: 0.8rem;">
                                                            <i class="bi bi-eye"></i> Revisar
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center text-muted py-3 small">
                                                <i class="bi bi-journal-text h4 d-block text-secondary mb-2"></i> 
                                                No hay tareas independientes registradas.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif(request('tab') === 'calificaciones')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">Consolidado de Calificaciones</h5>
                        <button class="btn btn-sm btn-outline-success" onclick="window.print();">
                            <i class="bi bi-printer me-1"></i> Imprimir Registro
                        </button>
                    </div>

                    @if($students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-dark" style="font-size: 0.85rem;">
                                <thead class="table-light text-center text-uppercase font-weight-bold" style="font-size: 0.75rem;">
                                    <tr>
                                        <th rowspan="2" class="align-middle text-start" style="min-width: 220px;">Estudiante</th>
                                        @if($training->tasks && $training->tasks->count() > 0)
                                            <th colspan="{{ $training->tasks->count() }}" class="text-info bg-light">Tareas Entregables</th>
                                        @endif
                                        @if($training->assessments->count() > 0)
                                            <th colspan="{{ $training->assessments->count() }}" class="text-primary bg-light">Evaluaciones</th>
                                        @endif
                                        <th rowspan="2" class="align-middle bg-dark text-white" style="width: 75px;">Prom.</th>
                                    </tr>
                                    <tr>
                                        {{-- Columnas de Tareas --}}
                                        @if($training->tasks)
                                            @foreach($training->tasks as $task)
                                                <th class="fw-normal text-truncate small" style="max-width: 110px;" title="{{ $task->title }}">
                                                    {{ Str::limit($task->title, 12) }}
                                                </th>
                                            @endforeach
                                        @endif

                                        {{-- Columnas de Evaluaciones --}}
                                        @foreach($training->assessments as $assessment)
                                            <th class="fw-normal text-truncate small" style="max-width: 110px;" title="{{ $assessment->title }}">
                                                {{ Str::limit($assessment->title, 12) }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $enrollment)
                                        @php
                                            $student = $enrollment->student;
                                            $totalNotes = 0;
                                            $notesCount = 0;
                                        @endphp
                                        <tr>
                                            <td class="fw-bold text-secondary">
                                                {{ $student->person->first_names }} {{ $student->person->last_names }}
                                            </td>

                                            {{-- Buscar notas de Tareas --}}
                                            @if($training->tasks)
                                                @foreach($training->tasks as $task)
                                                    @php
                                                        $submission = $task->submissions->where('student_id', $student->student_id)->first();
                                                        $grade = $submission ? $submission->grade : null;
                                                        if(!is_null($grade)) {
                                                            $totalNotes += $grade;
                                                            $notesCount++;
                                                        }
                                                    @endphp
                                                    <td class="text-center @if(!is_null($grade)) {{ $grade >= 11 ? 'text-success fw-bold' : 'text-danger fw-bold' }} @else text-muted @endif">
                                                        {{ !is_null($grade) ? $grade : '-' }}
                                                    </td>
                                                @endforeach
                                            @endif

                                            {{-- Buscar notas de Evaluaciones --}}
                                            @foreach($training->assessments as $assessment)
                                                @php
                                                    $attempt = $assessment->attempts->where('student_id', $student->student_id)->max('score');
                                                    if(!is_null($attempt)) {
                                                        $totalNotes += $attempt;
                                                        $notesCount++;
                                                    }
                                                @endphp
                                                <td class="text-center @if(!is_null($attempt)) {{ $attempt >= 11 ? 'text-success fw-bold' : 'text-danger fw-bold' }} @else text-muted @endif">
                                                    {{ !is_null($attempt) ? $attempt : '-' }}
                                                </td>
                                            @endforeach

                                            {{-- Calcular promedio de la fila --}}
                                            @php
                                                $finalAverage = $notesCount > 0 ? round($totalNotes / $notesCount, 1) : 0;
                                            @endphp
                                            <td class="text-center fw-bold table-light {{ $finalAverage >= 11 ? 'text-success' : 'text-danger' }}">
                                                {{ $finalAverage > 0 ? $finalAverage : '-' }}
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
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="createAssessmentModal" tabindex="-1" aria-labelledby="createAssessmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="createAssessmentModalLabel">Nueva Evaluación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('teacher.assessments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="assessment-title" class="form-label fw-bold">Título</label>
                            <input type="text" name="title" id="assessment-title" class="form-control" required placeholder="Ej. Examen Parcial I">
                        </div>
                        <div class="form-group mb-3">
                            <label for="assessment-description" class="form-label">Descripción</label>
                            <textarea name="description" id="assessment-description" class="form-control" rows="3" placeholder="Instrucciones breves..."></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="assessment-start-date" class="form-label">Fecha de inicio</label>
                                <input type="date" name="start_date" id="assessment-start-date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="assessment-end-date" class="form-label">Fecha de fin</label>
                                <input type="date" name="end_date" id="assessment-end-date" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="assessment-allowed-attempts" class="form-label">Intentos permitidos</label>
                            <input type="number" name="allowed_attempts" id="assessment-allowed-attempts" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="assessment-time-limit" class="form-label">Límite de Tiempo (Minutos)</label>
                            <input type="number" name="time_limit" id="assessment-time-limit" class="form-control" min="0" value="0">
                            <small class="form-text text-muted">Usa 0 o vacío para el tiempo estándar (60 min).</small>
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

    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-success" id="createTaskModalLabel">Nueva Tarea Entregable</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('teacher.tasks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $training->training_id }}">
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
                                <label for="task-due-date" class="form-label">Fecha Límite</label>
                                <input type="date" name="delivery_date" id="task-due-date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="task-max-score" class="form-label">Puntaje Máximo</label>
                                <input type="number" name="max_score" id="task-max-score" class="form-control" min="0" value="20" required>
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
@endsection