@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Mis cursos</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover">
                <thead>
                    <tr>
                        <th class="align-middle">Avatar</th>
                        <th class="align-middle">Curso</th>
                        <th class="align-middle">Detalles</th>
                        <th class="align-middle">Estado</th>
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
                            </td>

                            <td class="align-middle">
                                <div class="text-muted small">
                                    Alumnos matriculados: {{ $course->trainings->sum(fn($training) => $training->enrollments->count()) ?? 0 }}<br>
                                    Precio: S/ {{ number_format($course->reference_price, 2) }}
                                </div>
                            </td>

                            <td class="align-middle">
                                <span class="badge bg-success">Activo</span>
                            </td>

                            <td class="align-middle text-end">
                                <button class="btn btn-sm btn-info" onclick="viewDetails({{ $course->course_id }})">
                                    <i class="bi bi-eye"></i> Ver detalles
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No tienes cursos asignados aún
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function viewDetails(courseId) {
            // Lógica para ver detalles del curso (puede redirigir o abrir modal)
            window.location.href = `/teacher/courses/${courseId}`;
        }
    </script>

@endsection