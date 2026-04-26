<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Courses Report</title>
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
</head>

<body class="report report-summary">

    <h2 class="report-title">Courses Report</h2>

    <table class="report-table report-summary centered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Hours</th>
                <th>Price</th>
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