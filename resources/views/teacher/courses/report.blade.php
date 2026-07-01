@extends('layouts.app')

@section('noSidebar')@endsection
@section('title', 'Reporte de Calificaciones')

@section('content')
    <style>
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
            }

            #wrapper,
            .sidebar,
            .topbar,
            footer,
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

            .container-fluid,
            #content,
            #content-wrapper {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                background: transparent !important;
                box-shadow: none !important;
                border: none !important;
            }

            .report-content {
                margin: 0 !important;
                padding: 0 !important;
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

            table th {
                text-transform: uppercase !important;
                font-weight: 600 !important;
            }

            table td:first-child,
            table th:first-child {
                text-align: left !important;
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
        }
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
            <div>
                <a href="{{ route('teacher.courses.show', $training->training_id) }}" class="btn btn-sm btn-outline-secondary mb-2 mb-md-0">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button id="downloadReportPdf" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Imprimir
                </button>
            </div>
        </div>

        <div id="report-content" class="report-content">
            <div class="mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
                    <div>
                        <h1 class="h4 mb-1">Reporte de Calificaciones</h1>
                        <p class="mb-1">Curso: <strong>{{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}</strong></p>
                        <p class="mb-1">Docente: <strong>{{ $training->teacher->person->first_names ?? '' }} {{ $training->teacher->person->last_names ?? '' }}</strong></p>
                        <p class="mb-1">Código: <strong>{{ $training->code ?? 'N/A' }}</strong> | Modalidad: <strong>{{ ucfirst($training->modality) }}</strong></p>
                        <p class="mb-1">Estudiantes registrados: <strong>{{ $students->count() }}</strong></p>
                        <p class="text-muted mb-0">Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-dark" style="font-size: 0.9rem;">
                    <thead class="table-light text-center text-uppercase font-weight-bold" style="font-size: 0.8rem;">
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
                            @if($training->tasks)
                                @foreach($training->tasks as $task)
                                    <th class="fw-normal text-truncate small" style="max-width: 110px;" title="{{ $task->title }}">
                                        {{ Str::limit($task->title, 12) }}
                                    </th>
                                @endforeach
                            @endif

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
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const downloadBtn = document.getElementById('downloadReportPdf');
                if (!downloadBtn) return;

                downloadBtn.addEventListener('click', function () {
                    const element = document.getElementById('report-content');
                    const filename = 'Reporte-Calificaciones-{{ \Illuminate\Support\Str::slug($training->course->title, '-') }}.pdf';

                    const options = {
                        margin:       [15, 15, 15, 15],
                        filename:     filename,
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 2, useCORS: true },
                        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    };

                    html2pdf().set(options).from(element).save();
                });
            });
        </script>
    @endpush
@endsection
