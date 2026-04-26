<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Especialidades</title>
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
</head>
<body class="report report-specialties">
    <div class="header">
        <h1>Reporte de Especialidades</h1>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table class="report-table report-specialties left-aligned">
        <thead>
            <tr>
                <th>ID</th>
                <th>Especialidad</th>
                <th>Cantidad de Cursos</th>
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($especialidades as $esp)
            <tr>
                <td>{{ $esp->idespecialidad }}</td>
                <td>{{ $esp->especialidad }}</td>
                <td>{{ $esp->cursos_count }}</td>
                <td>{{ $esp->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Sistema LMS - Systematic</p>
    </div>
</body>
</html>