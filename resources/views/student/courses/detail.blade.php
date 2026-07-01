@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row g-4 align-items-start">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 overflow-hidden rounded-4">
                @if(!empty($course->banner_path))
                    <img src="{{ asset('storage/'.$course->banner_path) }}?v={{ file_exists(storage_path('app/public/'.$course->banner_path)) ? filemtime(storage_path('app/public/'.$course->banner_path)) : time() }}"
                         alt="{{ $course->title }}"
                         class="img-fluid w-100"
                         style="height: 320px; object-fit: cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center text-white fw-bold" style="height: 320px; background: linear-gradient(135deg, #0d6efd, #4f46e5); font-size: 3rem;">
                        {{ strtoupper(substr($course->title ?? 'C', 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4 p-xl-5">
                    <div class="mb-3">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Curso</span>
                    </div>

                    <h1 class="h3 fw-bold text-dark mb-3">{{ $course->title }}</h1>

                    <div class="d-flex flex-wrap gap-3 mb-4 text-muted small">
                        @if(!empty(optional($course->specialty)->specialty))
                            <span><i class="bi bi-tag-fill me-1"></i>{{ $course->specialty->specialty }}</span>
                        @endif

                        @if(!empty($training->modality))
                            <span><i class="bi bi-laptop me-1"></i>{{ $training->modality }}</span>
                        @endif

                        @if(!empty($course->hours_count))
                            <span><i class="bi bi-clock-history me-1"></i>{{ $course->hours_count }} h</span>
                        @endif
                    </div>

                    @if($isEnrolled)
                        <div class="alert alert-success mb-4" role="alert">
                            <strong>Ya estás matriculado en este curso.</strong>
                            <div class="mt-2 text-muted">Se muestra únicamente la información académica disponible.</div>
                        </div>
                    @else
                        @if(!empty($course->reference_price))
                            <div class="mb-4">
                                <span class="text-muted small d-block">Precio</span>
                                <span class="h4 fw-bold text-primary">{{ $course->reference_price }}</span>
                            </div>
                        @endif

                        <div class="alert alert-info mb-4" role="alert">
                            Para inscribirte en este curso, por favor contáctanos vía WhatsApp.
                        </div>
                    @endif

                    <div class="mt-5">
                        <h2 class="h5 fw-bold text-dark mb-3">Descripción</h2>
                        <p class="text-muted mb-0">{{ $course->description ?: 'La descripción del curso estará disponible próximamente.' }}</p>
                    </div>

                    <div class="mt-5">
                        <h2 class="h5 fw-bold text-dark mb-3">Lo que aprenderás</h2>
                        <div class="border rounded-3 p-3 bg-light-subtle text-muted">
                            La información del contenido del curso estará disponible próximamente.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
