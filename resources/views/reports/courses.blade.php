<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Cursos</title>
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
</head>

<body class="report report-courses">
    <div class="header">
        <h1>Reporte de Cursos</h1>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table class="report-table report-courses left-aligned">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Especialidad</th>
                <th>Descripción</th>
                <th>Horas</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{ $course->idcurso }}</td>
                <td>{{ $course->titulo }}</td>
                <td>{{ $course->especialidad->especialidad ?? 'N/A' }}</td>
                <td>{{ Str::limit($course->descripcion, 50) }}</td>
                <td>{{ $course->cantidadhoras }}</td>
                <td>S/ {{ number_format($course->precioreferencial, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema LMS - Systematic</p>
    </div>
</body>

</html>