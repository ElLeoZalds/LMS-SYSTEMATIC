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
        /* Anverso styles */
        .student-name {
            position: absolute;
            top: 330px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 38px;
            font-weight: bold;
            color: #0b1a30;
            text-transform: uppercase;
        }
        .course-title {
            position: absolute;
            top: 440px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            color: #0b1a30;
        }
        .cert-date {
            position: absolute;
            top: 525px;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 16px;
            color: #444;
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
            {{ $enrollment->training->course->title ?? '' }}
        </div>
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
            {{ $enrollment->training->course->title ?? '' }}
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
