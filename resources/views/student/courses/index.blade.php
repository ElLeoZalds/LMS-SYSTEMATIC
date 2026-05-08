@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Mis cursos</h1>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th class="align-middle">Avatar</th>
                        <th class="align-middle">Curso</th>
                        <th class="align-middle">Instructor</th>
                        <th class="align-middle">Progreso</th>
                        <th class="align-middle text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td class="align-middle pe-3">
                                <div class="avatar-circle rounded-circle bg-avatar-{{ ($loop->index % 4) + 1 }}">
                                    {{ strtoupper(substr($course->title, 0, 1)) }}
                                </div>
                            </td>

                            <td class="align-middle">
                                <div class="fw-bold">{{ $course->title }}</div>
                                <small class="text-muted">{{ $course->description ?? 'Sin descripción' }}</small>
                            </td>

                            <td class="align-middle">
                                {{ $course->teacher->name ?? 'Sin asignar' }}
                            </td>

                            <td class="align-middle">
                                <div class="progress" style="width: 100px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $course->progress_percentage ?? 0 }}%;"
                                        aria-valuenow="{{ $course->progress_percentage ?? 0 }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                        {{ $course->progress_percentage ?? 0 }}%
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle text-end">
                                <button class="btn btn-sm btn-success" onclick="continueLearning({{ $course->course_id }})">
                                    <i class="bi bi-play-circle me-1"></i>Continuar aprendiendo
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No estás matriculado en ningún curso aún
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function continueLearning(courseId) {
            // Lógica para continuar el curso (redirigir a la lección actual)
            window.location.href = `/student/courses/${courseId}/learn`;
        }
    </script>

@endsection