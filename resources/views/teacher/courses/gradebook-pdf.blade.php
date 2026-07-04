<!DOCTYPE html>
<html lang="es">
<head>
@php use Illuminate\Support\Str; @endphp
    <meta charset="UTF-8">
    <title>Gradebook</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
        th { background: #f5f5f5; }
        .name { text-align: left; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 6px; }
        .subtitle { color: #666; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="title">Gradebook del módulo</div>
    <div class="subtitle">{{ $training->course->title ?? 'Curso' }} · {{ $selectedModule?->title ?? 'General' }}</div>

    <table>
        <thead>
            <tr>
                <th class="name">Estudiante</th>
                @foreach($gradebook['tasks'] as $task)
                    <th>{{ Str::limit($task->title, 16) }}</th>
                @endforeach
                @foreach($gradebook['assessments'] as $assessment)
                    <th>{{ Str::limit($assessment->title, 16) }}</th>
                @endforeach
                <th>Promedio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gradebook['rows'] as $row)
                <tr>
                    <td class="name">{{ $row['student']->person->first_names ?? '' }} {{ $row['student']->person->last_names ?? '' }}</td>
                    @foreach($row['cells'] as $cell)
                        <td>{{ is_null($cell['value']) ? '-' : $cell['value'] }}</td>
                    @endforeach
                    <td>{{ is_null($row['average']) ? '-' : $row['average'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
