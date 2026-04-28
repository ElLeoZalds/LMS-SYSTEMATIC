@extends('layouts.app')

@section('title', 'Listado de cursos')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h2>Gestión de Cursos</h2>
            <div class="d-flex gap-2">
                <button id="openCourseModal" class="btn btn-success" type="button">
                    Crear curso
                </button>
                <a href="{{ route('courses.report') }}" class="btn btn-danger">
                    <i class="fa fa-file-pdf"></i> Generar reporte PDF
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th style="text-align: center;">ID</th>
                    <th style="text-align: center;">Título</th>
                    <th style="text-align: center;">Descripción</th>
                    <th style="text-align: center;">Horas</th>
                    <th style="text-align: center;">Precio</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr>
                    <td style="text-align: center;">{{ $course->idcurso }}</td>
                    <td style="text-align: center;">{{ $course->titulo }}</td>
                    <td style="text-align: center;">{{ Str::limit($course->descripcion, 80) }}</td>
                    <td style="text-align: center;">{{ $course->cantidadhoras }}</td>
                    <td style="text-align: center;">S/ {{ number_format($course->precioreferencial, 2) }}</td>
                    <td style="text-align: center;" class="text-nowrap">
                        <button type="button" class="btn btn-primary btn-sm edit-course-btn"
                            data-course-id="{{ $course->idcurso }}" data-course-title="{{ $course->titulo }}"
                            data-course-description="{{ $course->descripcion }}"
                            data-course-hours="{{ $course->cantidadhoras }}"
                            data-course-price="{{ $course->precioreferencial }}"
                            data-course-banner="{{ $course->pathbanner }}"
                            data-course-specialty="{{ $course->idespecialidad }}"
                            data-course-update-url="{{ route('courses.update', $course->idcurso) }}">
                            Editar
                        </button>
                        <a href="{{ route('courses.enrollments', $course->idcurso) }}" class="btn btn-success btn-sm">
                            Agregar estudiante
                        </a>
                        <form action="{{ route('courses.destroy', $course->idcurso) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este curso?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No se encontraron cursos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseModalTitle">Crear curso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="courseModalForm" method="POST" action="{{ route('courses.store') }}">
                @csrf
                <input type="hidden" name="_method" id="courseModalMethod" value="POST">
                <div class="modal-body">
                    <div id="courseModalErrors"></div>
                    <div class="mb-3">
                        <label for="modalSpecialty" class="form-label">Especialidad</label>
                        <select class="form-control" id="modalSpecialty" name="idespecialidad" required>
                            <option value="">Selecciona una especialidad</option>
                            @foreach($especialidades as $esp)
                            <option value="{{ $esp->idespecialidad }}">{{ $esp->especialidad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modalTitle" class="form-label">Título</label>
                        <input type="text" class="form-control" id="modalTitle" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="modalDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="modalDescription" name="descripcion" rows="4"
                            required></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modalHours" class="form-label">Horas</label>
                            <input type="number" class="form-control" id="modalHours" name="cantidadhoras" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modalPrice" class="form-label">Precio de referencia</label>
                            <input type="number" step="0.01" class="form-control" id="modalPrice"
                                name="precioreferencial" required>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="modalBanner" class="form-label">URL del banner (opcional)</label>
                        <input type="text" class="form-control" id="modalBanner" name="pathbanner">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="courseModalSubmit">Guardar curso</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var courseModal = new bootstrap.Modal(document.getElementById('courseModal'));
        var courseForm = document.getElementById('courseModalForm');
        var modalTitle = document.getElementById('courseModalTitle');
        var modalSubmit = document.getElementById('courseModalSubmit');
        var formMethod = document.getElementById('courseModalMethod');
        var openCourseModal = document.getElementById('openCourseModal');

        function resetModal() {
            courseForm.action = "{{ route('courses.store') }}";
            formMethod.value = 'POST';
            modalTitle.textContent = 'Crear curso';
            modalSubmit.textContent = 'Guardar curso';
            document.getElementById('modalSpecialty').value = '';
            document.getElementById('modalTitle').value = '';
            document.getElementById('modalDescription').value = '';
            document.getElementById('modalHours').value = '';
            document.getElementById('modalPrice').value = '';
            document.getElementById('modalBanner').value = '';
            document.getElementById('courseModalErrors').innerHTML = '';
        }

        openCourseModal.addEventListener('click', function() {
            resetModal();
            courseModal.show();
        });

        document.querySelectorAll('.edit-course-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                resetModal();
                courseForm.action = button.dataset.courseUpdateUrl;
                formMethod.value = 'PUT';
                modalTitle.textContent = 'Editar curso';
                modalSubmit.textContent = 'Actualizar curso';
                document.getElementById('modalSpecialty').value = button.dataset.courseSpecialty || '';
                document.getElementById('modalTitle').value = button.dataset.courseTitle || '';
                document.getElementById('modalDescription').value = button.dataset.courseDescription || '';
                document.getElementById('modalHours').value = button.dataset.courseHours || '';
                document.getElementById('modalPrice').value = button.dataset.coursePrice || '';
                document.getElementById('modalBanner').value = button.dataset.courseBanner || '';
                courseModal.show();
            });
        });
    });
</script>
@endpush