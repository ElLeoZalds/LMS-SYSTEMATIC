@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        <h2 class="mb-4">
            Estudiantes del curso: {{ $training->course->title }}
        </h2>

        <div class="card shadow-sm">
            <div class="card-body">

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Progreso</th>
                            <th>Última actividad</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($students as $enrollment)

                            @php
                                $progress = $enrollment->progress->avg('percentage') ?? 0;
                                $lastActivity = $enrollment->progress->max('activity_date');
                            @endphp

                            <tr>
                                <td>
                                    <strong>
                                        {{ optional($enrollment->student->person)->first_names }}
                                        {{ optional($enrollment->student->person)->last_names }}
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ optional($enrollment->student->person)->email }}
                                    </small>
                                </td>

                                <td style="width: 200px;">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%">
                                            {{ round($progress) }}%
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    {{ $lastActivity ?? '—' }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    No hay estudiantes inscritos
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>

    </div>

@endsection