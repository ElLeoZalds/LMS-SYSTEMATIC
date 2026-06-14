@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="mb-4">
            <h1 class="h3 mb-2 text-gray-800 fw-bold">Mis Capacitaciones</h1>
            <p class="text-muted small">Accede a tus cursos y gestiona tus estudiantes</p>
        </div>

        @if($trainings->count() > 0)
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row g-4">
                @foreach($trainings as $training)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm rounded-3 border-0 position-relative overflow-hidden course-card-clickable" data-url="{{ route('teacher.courses.show', $training->training_id) }}">
                            <div class="course-banner position-relative">
                                @if(!empty($training->course->banner_path))
                                    <div class="course-banner-image" style="background-image: url('{{ asset('storage/'.$training->course->banner_path) }}?v={{ file_exists(storage_path('app/public/'.$training->course->banner_path)) ? filemtime(storage_path('app/public/'.$training->course->banner_path)) : time() }}');"></div>
                                @else
                                    <div class="course-banner-fallback d-flex align-items-center justify-content-center text-white">
                                        {{ strtoupper(substr($training->course->title, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="course-banner-overlay"></div>
                                <button type="button" class="course-banner-view btn btn-sm btn-light text-dark"
                                    data-training-id="{{ $training->training_id }}"
                                    data-course-title="{{ $training->course->title }}"
                                    data-banner-url="{{ !empty($training->course->banner_path) ? asset('storage/'.$training->course->banner_path) . '?v=' . (file_exists(storage_path('app/public/'.$training->course->banner_path)) ? filemtime(storage_path('app/public/'.$training->course->banner_path)) : time()) : '' }}">
                                    Subir banner
                                </button>
                            </div>

                            <div class="card-body d-flex flex-column">
                            <div>
                                <h5 class="card-title fw-bold text-dark mb-2 line-clamp-2" style="font-size: 1.1rem;">
                                    {{ $training->course->title }}
                                </h5>
                                <p class="text-muted small mb-0">
                                    NRC: <strong class="text-dark">{{ $training->nrc ?? 'N/A' }}</strong>
                                </p>
                            </div>

                            <div class="row text-center mt-4 pt-3 border-top g-2 flex-grow-1 align-items-end">
                                <div class="col-6">
                                    <div class="h6 fw-bold text-primary mb-1">
                                        {{ $training->enrollments->count() }}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Alumnos</small>
                                </div>
                                <div class="col-6">
                                    <div class="h6 fw-bold text-success mb-1">
                                        {{ ucfirst($training->modality) }}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Modalidad</small>
                                </div>
                            </div>

                            <div class="mt-3">
                                <span class="badge bg-success">
                                    ✓ Activo
                                </span>
                            </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="bannerUploadForm" method="POST" enctype="multipart/form-data" action="">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="bannerModalLabel">Subir banner</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p class="small text-muted mb-3" id="bannerModalCourseTitle"></p>
                                <div class="mb-3">
                                    <label for="modalBannerInput" class="form-label">Elige una imagen .jpg o .png</label>
                                    <input id="modalBannerInput" name="banner" type="file" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                                </div>
                                <div id="bannerPreview" class="border rounded p-3 text-center" style="min-height: 180px; background: #f8f9fa;">
                                    <span class="text-muted">Selecciona un archivo para ver la previsualización aquí.</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" id="bannerUploadSubmit" class="btn btn-primary" disabled>Subir banner</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem;" class="text-muted"></i>
                    <h5 class="card-title mt-4 text-dark fw-bold">No hay capacitaciones</h5>
                    <p class="text-muted">No tienes cursos asignados aún. Contacta con el administrador.</p>
                </div>
            </div>
        @endif
    </div>

    <style>
        .card { transition: box-shadow 0.3s ease, transform 0.3s ease; }
        .card:hover { box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important; transform: translateY(-4px); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        a { color: inherit; text-decoration: none !important; }
        a:hover { color: inherit; }
        .course-banner { height: 160px; position: relative; overflow: hidden; background: #e9ecef; }
        .course-banner-image { background-size: cover; background-position: center; width: 100%; height: 100%; }
        .course-banner-fallback { background: #0d6efd; width: 100%; height: 100%; font-size: 2.5rem; font-weight: bold; opacity: 0.9; }
        .course-banner-overlay { position: absolute; inset: 0; background: rgba(0, 0, 0, 0.12); }
        .course-banner-view { position: absolute; bottom: 0.8rem; right: 0.8rem; z-index: 2; }
        .course-banner-view:hover { text-decoration: none; }
        .course-card-clickable { cursor: pointer; }
        .course-card-clickable .card-body { pointer-events: none; }
        .course-card-clickable button { pointer-events: auto; }
        #bannerPreview { max-height: 340px; overflow: hidden; }
        #bannerPreview img { max-width: 100%; height: auto; display: inline-block; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bannerModal = document.getElementById('bannerModal');
            const bannerForm = document.getElementById('bannerUploadForm');
            const bannerInput = document.getElementById('modalBannerInput');
            const bannerSubmit = document.getElementById('bannerUploadSubmit');
            const bannerPreview = document.getElementById('bannerPreview');
            const bannerCourseTitle = document.getElementById('bannerModalCourseTitle');

            document.querySelectorAll('.course-banner-view').forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.stopPropagation();
                    const trainingId = this.dataset.trainingId;
                    const courseTitle = this.dataset.courseTitle;
                    const bannerUrl = this.dataset.bannerUrl;

                    bannerForm.action = '{{ url('teacher/courses') }}/' + trainingId + '/banner';
                    bannerCourseTitle.textContent = 'Curso: ' + courseTitle;
                    bannerInput.value = '';
                    bannerSubmit.disabled = true;
                    bannerPreview.innerHTML = bannerUrl
                        ? '<img src="' + bannerUrl + '" alt="Previsualización del banner">'
                        : '<span class="text-muted">Selecciona un archivo para ver la previsualización aquí.</span>';

                    if (typeof $ === 'function') {
                        $('#bannerModal').modal('show');
                    }
                });
            });

            document.querySelectorAll('.course-card-clickable').forEach(function (card) {
                card.addEventListener('click', function (event) {
                    if (event.target.closest('.course-banner-view')) {
                        return;
                    }

                    const url = this.dataset.url;
                    if (url) {
                        window.location.href = url;
                    }
                });
            });

            bannerInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) {
                    bannerPreview.innerHTML = '<span class="text-muted">Selecciona un archivo para ver la previsualización aquí.</span>';
                    bannerSubmit.disabled = true;
                    return;
                }

                const validTypes = ['image/jpeg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    bannerPreview.innerHTML = '<span class="text-danger">Formato no permitido. Usa JPG o PNG.</span>';
                    bannerSubmit.disabled = true;
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    bannerPreview.innerHTML = '<img src="' + e.target.result + '" alt="Previsualización del banner">';
                };
                reader.readAsDataURL(file);
                bannerSubmit.disabled = false;
            });
        });
    </script>
@endsection
