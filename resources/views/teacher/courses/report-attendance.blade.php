@extends('layouts.app')

@section('noSidebar')@endsection
@section('title', 'Reporte de Asistencias')

@section('content')
    <style>
        .attendance-container {
            background: #fff;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table-responsive-custom {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }

        .table-responsive-custom::-webkit-scrollbar {
            height: 8px;
        }
        .table-responsive-custom::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .table-responsive-custom::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        .table-responsive-custom::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .attendance-report-table {
            table-layout: auto !important;
            width: 100%;
        }
        .col-idx { width: 45px; min-width: 45px; text-align: center; }
        .col-student { min-width: 240px; text-align: left; }
        .col-status { min-width: 110px; text-align: center; }
        .status-percent {
            font-size: 0.78rem;
            line-height: 1.15;
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

            #wrapper, .sidebar, .topbar, footer, .btn, .navbar, .nav-tabs {
                display: none !important;
                visibility: hidden !important;
            }

            .table-responsive-custom {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 10pt !important;
            }

            table th, table td {
                border: 1px solid #000 !important;
                padding: 0.35rem !important;
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

        <div id="report-content" class="attendance-container">
            <div class="mb-4">
                <div class="text-center mb-4">
                    <h1 class="h4 mb-1 fw-bold">LISTA DE ASISTENCIA</h1>
                    <p class="mb-1 small text-uppercase text-muted">SYSTEMATIC</p>
                </div>

                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-2">
                        <p class="mb-1"><strong>Docente:</strong> {{ $training->teacher->person->first_names ?? '' }} {{ $training->teacher->person->last_names ?? '' }}</p>
                        <p class="mb-1"><strong>Curso:</strong> {{ $training->course->title }}</p>
                        <p class="mb-0"><strong>Código:</strong> {{ $training->code ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12 col-md-6 mb-2">
                        <p class="mb-1"><strong>Modalidad:</strong> {{ ucfirst($training->modality) }}</p>
                        <p class="mb-1"><strong>Estudiantes registrados:</strong> {{ $training->enrollments->count() }}</p>
                        <p class="mb-1"><strong>Sesiones evaluadas:</strong> {{ $totalSchedules }}</p>
                        <p class="mb-0"><strong>Registros:</strong> {{ $totalAttendanceRecords }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="badge bg-success">✓ Presente</span>
                    <span class="badge bg-danger">✕ Ausente</span>
                    <span class="badge bg-info text-dark">J Justificado</span>
                    <span class="badge bg-warning text-dark">T Tardanza</span>
                </div>
            </div>

            @if($training->enrollments->count() > 0 && $schedules->count() > 0)
                <div class="table-responsive-custom">
                    <table class="table table-bordered table-hover align-middle text-dark attendance-report-table" style="font-size: 0.85rem;">
                        <thead class="table-light text-center text-uppercase" style="font-size: 0.75rem;">
                            <tr>
                                <th class="col-idx">#</th>
                                <th class="col-student">Alumno</th>
                                <th class="col-status">Presente</th>
                                <th class="col-status">Ausente</th>
                                <th class="col-status">Justificado</th>
                                <th class="col-status">Tardanza</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($training->enrollments as $index => $enrollment)
                                @php
                                    $summary = $attendanceSummary[$enrollment->enrollment_id] ?? [
                                        'counts' => ['present' => 0, 'absent' => 0, 'justified' => 0, 'late' => 0],
                                        'percentages' => ['present' => 0, 'absent' => 0, 'justified' => 0, 'late' => 0],
                                    ];
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="fw-bold text-start">{{ $enrollment->student->person->first_names }} {{ $enrollment->student->person->last_names }}</td>
                                    <td class="text-center">
                                        <div class="status-percent fw-bold text-success">{{ $summary['counts']['present'] }}</div>
                                        <div class="status-percent text-muted">{{ $summary['percentages']['present'] }}%</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="status-percent fw-bold text-danger">{{ $summary['counts']['absent'] }}</div>
                                        <div class="status-percent text-muted">{{ $summary['percentages']['absent'] }}%</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="status-percent fw-bold text-warning">{{ $summary['counts']['justified'] }}</div>
                                        <div class="status-percent text-muted">{{ $summary['percentages']['justified'] }}%</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="status-percent fw-bold text-warning">{{ $summary['counts']['late'] }}</div>
                                        <div class="status-percent text-muted">{{ $summary['percentages']['late'] }}%</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
                    const filename = 'Reporte-Asistencias-{{ \Illuminate\Support\Str::slug($training->course->title, "-") }}.pdf';

                    const options = {
                        margin:       [12, 12, 12, 12],
                        filename:     filename,
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 2, useCORS: true },
                        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
                    };

                    html2pdf().set(options).from(element).save();
                });
            });
        </script>
    @endpush
@endsection
