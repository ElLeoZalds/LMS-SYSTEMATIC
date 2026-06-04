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
                <i class="bi bi-arrow-left me-2"></i>Volver al Curso
            </a>
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
                            <i class="bi bi-book-fill me-2"></i>Evaluaciones
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=tareas"
                            class="nav-link @if(request('tab') === 'tareas') active @endif" id="tareas-tab"
                            role="tab">
                            <i class="bi bi-list-task me-2"></i>Tareas
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=calificaciones"
                            class="nav-link @if(request('tab') === 'calificaciones') active @endif" id="calificaciones-tab"
                            role="tab">
                            <i class="bi bi-check-circle-fill me-2"></i>Calificaciones
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
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
                        <div class="mb-4">
                            <div class="course-banner mb-3">
                                @if(!empty($training->course->banner_path))
                                    <div class="course-banner-image" style="background-image: url('{{ asset('storage/'.$training->course->banner_path) }}?v={{ file_exists(storage_path('app/public/'.$training->course->banner_path)) ? filemtime(storage_path('app/public/'.$training->course->banner_path)) : time() }}'); height:200px;"></div>
                                @else
                                    <div class="course-banner-fallback d-flex align-items-center justify-content-center text-white" style="height:200px;">
                                        {{ strtoupper(substr($training->course->title, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="course-banner-overlay"></div>
                            </div>
                        </div>
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
                                                <td>
                                                    <div class="fw-bold">{{ $assessment->title }}</div>
                                                    @if(!empty($assessment->description))
                                                        <small class="text-muted d-block">{{ $assessment->description }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $assessment->start_date ? \Carbon\Carbon::parse($assessment->start_date)->format('d/m/Y') : 'Sin fecha' }}</td>
                                                <td class="text-center">{{ $assessment->end_date ? \Carbon\Carbon::parse($assessment->end_date)->format('d/m/Y') : 'Sin fecha' }}</td>
                                                <td class="text-center">{{ $assessment->allowed_attempts }}</td>
                                                <td class="text-center">
                                                    <span class="badge @if($assessment->active) bg-success @else bg-secondary @endif">{{ $assessment->active ? 'Activo' : 'Inactivo' }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex flex-column align-items-end gap-1">
                                                        <a href="{{ route('teacher.assessments.show', $assessment->assessment_id) }}" class="btn btn-sm btn-info text-white">
                                                            <i class="bi bi-pencil-square"></i> Gestionar Preguntas
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-primary edit-assessment-btn" data-assessment='{{ json_encode(["id" => $assessment->assessment_id, "title" => $assessment->title, "description" => $assessment->description, "start_date" => $assessment->start_date ? $assessment->start_date->format('Y-m-d') : null, "end_date" => $assessment->end_date ? $assessment->end_date->format('Y-m-d') : null, "allowed_attempts" => $assessment->allowed_attempts, "time_limit" => $assessment->time_limit ]) }}'>
                                                            <i class="bi bi-pencil"></i> Editar
                                                        </button>
                                                        <form action="{{ route('teacher.assessments.destroy', $assessment->assessment_id) }}" method="POST" class="swal-confirm" data-message="¿Estás seguro de eliminar esta evaluación? Se eliminarán también los intentos asociados.">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="bi bi-trash"></i> Eliminar
                                                            </button>
                                                        </form>
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
                                                <th class="text-center">Vence</th>
                                                <th class="text-center">Entregas</th>
                                                <th class="text-center">Por revisar</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($training->tasks as $task)
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
                                                    <td class="text-center text-secondary small">{{ $task->due_date ? $task->due_date->format('d/m/Y H:i') : 'Sin fecha' }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-light text-dark border px-2 py-1">{{ $task->submissions->count() }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning text-dark px-2 py-1">{{ $task->submissions->whereNull('grade')->count() }} por revisar</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex flex-column align-items-end gap-1">
                                                            <a href="{{ route('teacher.tasks.submissions', $task->task_id) }}" class="btn btn-sm btn-success">
                                                                <i class="bi bi-eye"></i> Revisar
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-primary edit-task-btn" data-task='{{ json_encode(["id" => $task->task_id, "title" => $task->title, "description" => $task->description, "due_date" => $task->due_date ? $task->due_date->format('Y-m-d') : null, "file_path" => $task->file_path ?? null]) }}'>
                                                                <i class="bi bi-pencil"></i> Editar
                                                            </button>
                                                            <form action="{{ route('teacher.tasks.destroy', $task->task_id) }}" method="POST" class="swal-confirm" data-message="¿Deseas eliminar esta tarea? Las entregas asociadas también se eliminarán automáticamente.">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="bi bi-trash"></i> Eliminar
                                                                </button>
                                                            </form>
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
                            <h5 class="fw-bold text-dark mb-1">Consolidado de Calificaciones</h5>
                            <p class="text-muted mb-0">Curso: {{ $training->course->title }} | Código: {{ $training->course->code ?? 'N/A' }} | Modalidad: {{ ucfirst($training->modality) }}</p>
                        </div>
                        <a href="{{ route('teacher.courses.report', $training->training_id) }}" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-printer me-1"></i> Imprimir Registro
                        </a>
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
                                                        $submission = $task->submissions->where('student_id', $student->user_id)->first();
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
                                                    $attempt = $assessment->attempts->filter(function($a) use($student) {
                                                        return optional($a->enrollment)->student_id == $student->user_id;
                                                    })->max('score');
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
                <form action="{{ route('teacher.assessments.store') }}" method="POST" id="createAssessmentForm">
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
                                <input type="date" name="start_date" id="assessment-start-date" class="form-control" required min="{{ now()->toDateString() }}" @if($training->end_date) max="{{ $training->end_date->format('Y-m-d') }}" @endif>
                            </div>
                            <div class="col-md-6">
                                <label for="assessment-end-date" class="form-label">Fecha de fin</label>
                                <input type="date" name="end_date" id="assessment-end-date" class="form-control" required min="{{ now()->toDateString() }}" @if($training->end_date) max="{{ $training->end_date->format('Y-m-d') }}" @endif>
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

    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
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
                                <input type="date" name="delivery_date" id="task-due-date" class="form-control" required min="{{ now()->toDateString() }}" @if($training->end_date) max="{{ $training->end_date->format('Y-m-d') }}" @endif>
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
                startDateInput.min = todayDate;
                endDateInput.min = todayDate;

                startDateInput.addEventListener('change', function() {
                    endDateInput.min = this.value || todayDate;
                    if (endDateInput.value && endDateInput.value < endDateInput.min) {
                        endDateInput.value = endDateInput.min;
                    }
                });
            }

            const taskDueDateInput = document.getElementById('task-due-date');
            if (taskDueDateInput) {
                taskDueDateInput.min = todayDate;
                if (taskDueDateInput.value && taskDueDateInput.value < todayDate) {
                    taskDueDateInput.value = todayDate;
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
@endsection