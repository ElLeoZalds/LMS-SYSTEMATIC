@extends('layouts.app')

@section('noSidebar')@endsection
@section('title', 'Reporte de Asistencias')

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

            .attendance-chunk {
                display: block !important;
                page-break-after: always !important;
                break-after: page !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .attendance-chunk:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 10.5pt !important;
                table-layout: fixed !important;
            }

            table th,
            table td {
                border: 1px solid #000 !important;
                padding: 0.35rem !important;
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
                <a href="{{ route('teacher.courses.show', $training->training_id) }}?tab=asistencias" class="btn btn-sm btn-outline-secondary mb-2 mb-md-0">
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
                <div class="text-center mb-4">
                    <h1 class="h4 mb-1">LISTA DE ASISTENCIA</h1>
                    <p class="mb-1 small text-uppercase">Datos de la escuela</p>
                </div>

                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-2">
                        <p class="mb-1"><strong>Docente:</strong> {{ $training->teacher->person->first_names ?? '' }} {{ $training->teacher->person->last_names ?? '' }}</p>
                        <p class="mb-1"><strong>Curso:</strong> {{ $training->course->title }}</p>
                        <p class="mb-0"><strong>Código:</strong> {{ $training->course->code ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <p class="mb-1"><strong>Modalidad:</strong> {{ ucfirst($training->modality) }}</p>
                        <p class="mb-1"><strong>Estudiantes registrados:</strong> {{ $training->enrollments->count() }}</p>
                        <p class="mb-0"><strong>Registros:</strong> {{ $totalAttendanceRecords }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="badge bg-success">✓ = Presente</span>
                    <span class="badge bg-danger">✕ = Ausente</span>
                    <span class="badge bg-warning text-dark">! = Tarde</span>
                    <span class="badge bg-warning text-dark">J = Justificado</span>
                </div>
            </div>

            @php
                $scheduleChunks = $schedules->chunk(4);
            @endphp

            @if($training->enrollments->count() > 0 && $schedules->count() > 0)
                @foreach($scheduleChunks as $pageIndex => $scheduleChunk)
                    <div class="attendance-chunk">
                        <div class="mb-2">
                        <h6 class="fw-bold">Fechas de asistencia (parte {{ $pageIndex + 1 }} de {{ $scheduleChunks->count() }})</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-dark" style="font-size: 0.85rem; table-layout: fixed;">
                            <thead class="table-light text-center text-uppercase" style="font-size: 0.75rem;">
                                <tr>
                                    <th style="width: 40px; white-space: nowrap;">#</th>
                                    <th style="min-width: 220px; white-space: normal; text-align: left;">Alumno</th>
                                    @foreach($scheduleChunk as $schedule)
                                        <th style="width: 9%; white-space: nowrap;">{{ $schedule->date ? $schedule->date->format('d/m') : 'Sin fecha' }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($training->enrollments as $index => $enrollment)
                                    @php
                                        $rowNumber = $index + 1;
                                    @endphp
                                    <tr>
                                        <td class="text-center" style="width: 40px;">{{ $rowNumber }}</td>
                                        <td class="fw-bold text-start" style="min-width: 220px;">{{ $enrollment->student->person->first_names }} {{ $enrollment->student->person->last_names }}</td>
                                        @foreach($scheduleChunk as $schedule)
                                            @php
                                                $attendance = $attendanceMap[$enrollment->enrollment_id][$schedule->schedule_id] ?? null;
                                                $status = $attendance ? ($attendance->attendance_status ?? $attendance->attendance) : null;
                                                $status = is_string($status) ? strtolower($status) : null;
                                            @endphp
                                            <td class="text-center">
                                                @if($status === 'p' || $status === 'present')
                                                    <span class="text-success fw-bold">✓</span>
                                                @elseif($status === 'a' || $status === 'absent')
                                                    <span class="text-danger fw-bold">✕</span>
                                                @elseif($status === 'j' || $status === 'justified')
                                                    <span class="text-warning fw-bold">J</span>
                                                @elseif($status === 'late')
                                                    <span class="text-warning fw-bold">!</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info text-center mb-0" role="alert">
                    <i class="bi bi-info-circle me-2"></i>No hay fechas de asistencia registradas o no hay estudiantes matriculados en este curso.
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
                    const filename = 'Reporte-Asistencias-{{ \Illuminate\Support\Str::slug($training->course->title, '-') }}.pdf';

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
