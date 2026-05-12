@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="mb-4">
            <a href="{{ route('teacher.courses') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left me-2"></i>Volver a mis cursos
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-4">
                <h1 class="h3 mb-4 text-gray-800">Tomar Asistencia - {{ $training->course->title }}</h1>

                <form action="{{ route('teacher.attendance.store') }}" method="POST" class="row g-3">
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $training->training_id }}">

                    <div class="mb-3">
                        <button type="button" class="btn btn-success" onclick="markAllPresent()">
                            Marcar Todos como Asistidos
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-dark small fw-bold">Estudiante</th>
                                    <th class="text-dark small fw-bold text-center">Presente</th>
                                    <th class="text-dark small fw-bold text-center">Ausente</th>
                                    <th class="text-dark small fw-bold text-center">Justificado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $enrollment)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="text-dark">
                                                {{ optional($enrollment->student->person)->first_names }}
                                                {{ optional($enrollment->student->person)->last_names }}
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="radio" name="attendances[{{ $loop->index }}][status]" value="P"
                                                checked>
                                            <input type="hidden" name="attendances[{{ $loop->index }}][student_id]"
                                                value="{{ $enrollment->student_id }}">
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="radio" name="attendances[{{ $loop->index }}][status]" value="A">
                                        </td>
                                        <td class="align-middle text-center">
                                            <input type="radio" name="attendances[{{ $loop->index }}][status]" value="J">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Guardar Asistencia
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function markAllPresent() {
                var radios = document.querySelectorAll('input[type="radio"][value="P"]');
                radios.forEach(function(radio) {
                    radio.checked = true;
                });
            }
        </script>
@endsection