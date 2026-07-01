@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="mb-4">
                        <h1 class="h3 fw-bold text-dark mb-2">Confirmación de inscripción</h1>
                        <p class="text-muted mb-0">Estás a un paso de completar tu inscripción. Revisa la información antes de continuar con el pago.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded-4 p-4 h-100">
                                <h2 class="h5 fw-bold text-dark mb-3">Información del curso</h2>

                                @if(!empty($course->banner_path))
                                    <img src="{{ asset('storage/'.$course->banner_path) }}?v={{ file_exists(storage_path('app/public/'.$course->banner_path)) ? filemtime(storage_path('app/public/'.$course->banner_path)) : time() }}"
                                         alt="{{ $course->title }}"
                                         class="img-fluid rounded-3 mb-3"
                                         style="height: 220px; width: 100%; object-fit: cover;">
                                @endif

                                <h3 class="h5 fw-bold text-dark mb-2">{{ $course->title }}</h3>

                                <div class="text-muted small">
                                    @if(!empty(optional($course->specialty)->specialty))
                                        <p class="mb-2"><i class="bi bi-tag-fill me-1"></i>{{ $course->specialty->specialty }}</p>
                                    @endif

                                    @if(!empty($training->modality))
                                        <p class="mb-2"><i class="bi bi-laptop me-1"></i>{{ $training->modality }}</p>
                                    @endif

                                    @if(!empty($course->hours_count))
                                        <p class="mb-2"><i class="bi bi-clock-history me-1"></i>{{ $course->hours_count }} h</p>
                                    @endif

                                    @if(!empty($course->reference_price))
                                        <p class="mb-0"><i class="bi bi-cash-stack me-1"></i>{{ $course->reference_price }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="border rounded-4 p-4 h-100">
                                <h2 class="h5 fw-bold text-dark mb-3">Información del estudiante</h2>
                                <div class="text-muted small">
                                    <p class="mb-2"><strong class="text-dark">Nombre:</strong> {{ $studentName }}</p>
                                    @if(!empty($studentEmail))
                                        <p class="mb-0"><strong class="text-dark">Correo:</strong> {{ $studentEmail }}</p>
                                    @endif
                                </div>

                                <hr class="my-4">

                                <h2 class="h5 fw-bold text-dark mb-3">Resumen</h2>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Curso seleccionado</span>
                                    <span class="fw-semibold text-dark">{{ $course->title }}</span>
                                </div>
                                @if(!empty($course->reference_price))
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Precio</span>
                                        <span class="fw-semibold text-dark">{{ $course->reference_price }}</span>
                                    </div>
                                @endif

                                <div class="alert alert-light border mb-0 mt-3" role="alert">
                                    La inscripción aún no ha sido completada. Este paso solo revisa la información antes de continuar con el pago.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4" role="alert">
                        Para completar tu inscripción, contáctanos vía WhatsApp y te ayudamos a formalizarla.
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="{{ route('student.courses.show', $training->training_id) }}" class="btn btn-outline-secondary">Volver al curso</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
