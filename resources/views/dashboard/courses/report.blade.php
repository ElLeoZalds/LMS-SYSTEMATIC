<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Courses Report</title>
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

    <h2>Courses Report</h2>

    <table>
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