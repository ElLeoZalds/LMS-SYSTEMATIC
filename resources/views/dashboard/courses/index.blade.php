@extends('layouts.app')

@section('title', 'Course List')

@section('content')
    <div class="container mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2>Course List</h2>
                <p class="text-muted">Manage courses directly from this page using the modal editor.</p>
            </div>
            <div class="d-flex gap-2">
                <button id="openCourseModal" class="btn btn-success" type="button">
                    Create Course
                </button>
                <a href="{{ route('courses.report') }}" class="btn btn-danger">
                    <i class="fa fa-file-pdf"></i> Generate PDF Report
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Hours</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->idcurso }}</td>
                            <td>{{ $course->titulo }}</td>
                            <td>{{ Str::limit($course->descripcion, 80) }}</td>
                            <td>{{ $course->cantidadhoras }}</td>
                            <td>S/ {{ number_format($course->precioreferencial, 2) }}</td>
                            <td class="text-nowrap">
                                <button type="button" class="btn btn-primary btn-sm edit-course-btn"
                                    data-course-id="{{ $course->idcurso }}" data-course-title="{{ $course->titulo }}"
                                    data-course-description="{{ $course->descripcion }}"
                                    data-course-hours="{{ $course->cantidadhoras }}"
                                    data-course-price="{{ $course->precioreferencial }}"
                                    data-course-banner="{{ $course->pathbanner }}"
                                    data-course-specialty="{{ $course->idespecialidad }}"
                                    data-course-update-url="{{ route('courses.update', $course->idcurso) }}">
                                    Edit
                                </button>
                                <a href="{{ route('courses.enrollments', $course->idcurso) }}" class="btn btn-success btn-sm">
                                    Add student
                                </a>
                                <form action="{{ route('courses.destroy', $course->idcurso) }}" method="POST"
                                    style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No courses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Course modal -->
    <div class="modal fade" id="courseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="courseModalTitle">Create Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="courseModalForm" method="POST" action="{{ route('courses.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="courseModalMethod" value="POST">
                    <div class="modal-body">
                        <div id="courseModalErrors"></div>
                        <div class="mb-3">
                            <label for="modalSpecialty" class="form-label">Specialty</label>
                            <select class="form-control" id="modalSpecialty" name="idespecialidad" required>
                                <option value="">Select a specialty</option>
                                @foreach($especialidades as $esp)
                                    <option value="{{ $esp->idespecialidad }}">{{ $esp->especialidad }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modalTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="modalTitle" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="modalDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="modalDescription" name="descripcion" rows="4"
                                required></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="modalHours" class="form-label">Hours</label>
                                <input type="number" class="form-control" id="modalHours" name="cantidadhoras" required>
                            </div>
                            <div class="col-md-6">
                                <label for="modalPrice" class="form-label">Reference Price</label>
                                <input type="number" step="0.01" class="form-control" id="modalPrice"
                                    name="precioreferencial" required>
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="modalBanner" class="form-label">Banner URL (optional)</label>
                            <input type="text" class="form-control" id="modalBanner" name="pathbanner">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="courseModalSubmit">Save Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var courseModal = new bootstrap.Modal(document.getElementById('courseModal'));
            var courseForm = document.getElementById('courseModalForm');
            var modalTitle = document.getElementById('courseModalTitle');
            var modalSubmit = document.getElementById('courseModalSubmit');
            var formMethod = document.getElementById('courseModalMethod');
            var openCourseModal = document.getElementById('openCourseModal');

            function resetModal() {
                courseForm.action = "{{ route('courses.store') }}";
                formMethod.value = 'POST';
                modalTitle.textContent = 'Create Course';
                modalSubmit.textContent = 'Save Course';
                document.getElementById('modalSpecialty').value = '';
                document.getElementById('modalTitle').value = '';
                document.getElementById('modalDescription').value = '';
                document.getElementById('modalHours').value = '';
                document.getElementById('modalPrice').value = '';
                document.getElementById('modalBanner').value = '';
                document.getElementById('courseModalErrors').innerHTML = '';
            }

            openCourseModal.addEventListener('click', function () {
                resetModal();
                courseModal.show();
            });

            document.querySelectorAll('.edit-course-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    resetModal();
                    courseForm.action = button.dataset.courseUpdateUrl;
                    formMethod.value = 'PUT';
                    modalTitle.textContent = 'Edit Course';
                    modalSubmit.textContent = 'Update Course';
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