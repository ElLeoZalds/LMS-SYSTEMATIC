<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado - {{ $enrollment->training->course->title }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #111;
        }
        .page {
            position: relative;
            width: 297mm;
            height: 210mm;
            page-break-after: always;
            box-sizing: border-box;
        }
        .page-back {
            page-break-after: avoid;
        }
        .bg-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 297mm;
            height: 210mm;
            z-index: -1;
        }
        /*
         * ─── Posicionamiento del anverso (PDF A4 landscape 297×210mm) ─────
         * Conversión desde porcentajes medidos sobre la imagen 1024×723 px:
         *   top_mm  = %Y / 100 × 210mm
         *   left_mm = %X / 100 × 297mm
         *
         * Se usan mm en lugar de px para independencia de DPI.
         * wkhtmltopdf procesa la página en unidades de punto tipográfico;
         * px arbitrarios en el código anterior provocaban desplazamiento
         * sistemático porque el escalado DPI no coincidía con el PDF.
         * ─────────────────────────────────────────────────────────────────
         */

        /* NOMBRE DEL ESTUDIANTE
         * %Y medido = 42.10%  →  88.41mm
         * Centrado en X: left:50%, transform translateX(-50%) */
        .student-name {
            position: absolute;
            top: 88.41mm;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #0b1a30;
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* NOMBRE DEL CURSO
         * %Y medido = 62.50%  →  131.25mm
         * Zona horizontal izquierda: 24%-68% (ancho 44%)
         * left:24%, width:44%, text-align:center */
        .course-title {
            position: absolute;
            top: 131.25mm;
            left: 24%;
            width: 44%;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #0b1a30;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* CÓDIGO DEL CERTIFICADO
         * Mismo Y que el curso (131.25mm).
         * Zona horizontal derecha desde 63%. */
        .cert-code {
            position: absolute;
            top: 131.25mm;
            left: 63%;
            font-size: 10px;
            font-weight: 600;
            color: #444;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        /* FECHA
         * %Y medido = 92.15%  →  193.52mm
         * Centrada en X. */
        .cert-date {
            position: absolute;
            top: 193.52mm;
            left: 50%;
            transform: translateX(-50%);
            width: 55%;
            text-align: center;
            font-size: 11px;
            color: #444;
            white-space: nowrap;
        }
        /* Reverso styles */
        .reverso-field {
            position: absolute;
            font-size: 15px;
            color: #111;
            font-weight: bold;
        }
        .actividad {
            top: 142px;
            left: 195px;
        }
        .modulo {
            top: 196px;
            left: 175px;
        }
        .duracion {
            top: 248px;
            left: 195px;
        }
        .fecha {
            top: 302px;
            left: 275px;
        }
        .nota {
            top: 354px;
            left: 145px;
        }
        .qr-container {
            position: absolute;
            top: 90px;
            right: 85px;
            width: 110px;
            height: 110px;
        }
        .qr-container svg {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>

    <!-- ANVERSO -->
    <div class="page">
        @if(file_exists($bgAnverso))
            <img src="{{ $bgAnverso }}" class="bg-img" alt="Anverso">
        @endif
        <div class="student-name">
            {{ $enrollment->student->person->first_names ?? '' }} {{ $enrollment->student->person->last_names ?? '' }}
        </div>
        <div class="course-title">
            {{ optional($enrollment->training->course)->title ?? '' }}{{ optional($enrollment->training->start_date)->format(' (Y-m)') }}
        </div>
        <div class="cert-code">{{ $certificateCode }}</div>
        <div class="cert-date">
            Otorgado el {{ \Carbon\Carbon::parse($enrollment->enrollment_date)->format('d \d\e F \d\e Y') }}
        </div>
    </div>

    <!-- REVERSO -->
    <div class="page page-back">
        @if(file_exists($bgReverso))
            <img src="{{ $bgReverso }}" class="bg-img" alt="Reverso">
        @endif
        
        <div class="reverso-field actividad">
            {{ optional($enrollment->training->course)->title ?? '' }}{{ optional($enrollment->training->start_date)->format(' (Y-m)') }}
        </div>
        <div class="reverso-field modulo">
            {{ $enrollment->training->course->specialty->specialty ?? 'General' }}
        </div>
        <div class="reverso-field duracion">
            {{ $enrollment->training->course->hours_count ?? 0 }} horas académicas
        </div>
        <div class="reverso-field fecha">
            {{ \Carbon\Carbon::parse($enrollment->enrollment_date)->format('d/m/Y') }}
        </div>
        <div class="reverso-field nota">
            {{ $averageGrade }}
        </div>

        <div class="qr-container">
            {!! $qrCode !!}
        </div>
    </div>

</body>
</html>
