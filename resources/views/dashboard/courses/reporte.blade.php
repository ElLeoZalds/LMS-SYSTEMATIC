<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Cursos</title>
</head>

<body>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #000000;
            color: #ffffff;
            padding: 8px;
            text-align: center;
        }

        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }
    </style>

    <h2>Reporte General de Cursos</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Título</th>
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
                <td>{{ $course->descripcion }}</td>
                <td>{{ $course->cantidadhoras }}</td>
                <td>S/ {{ $course->precioreferencial }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>