@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="mb-4">
                        <h1 class="h3 fw-bold text-dark mb-2">Resumen del pedido</h1>
                        <p class="text-muted mb-0">Aquí puedes revisar la información seleccionada antes de continuar.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded-4 p-4 h-100">
                                <h2 class="h5 fw-bold text-dark mb-3">Curso</h2>

                                @if(!empty($course->banner_path))
                                    <img src="{{ asset('storage/'.$course->banner_path) }}?v={{ file_exists(storage_path('app/public/'.$course->banner_path)) ? filemtime(storage_path('app/public/'.$course->banner_path)) : time() }}"
                                         alt="{{ $course->title }}"
                                         class="img-fluid rounded-3 mb-3"
                                         style="height: 220px; width: 100%; object-fit: cover;">
                                @endif

                                <h3 class="h6 fw-bold text-dark mb-2">{{ $course->title }}</h3>
                                @if(!empty(optional($course->specialty)->specialty))
                                    <p class="text-muted small mb-2"><i class="bi bi-tag-fill me-1"></i>{{ $course->specialty->specialty }}</p>
                                @endif
                                @if(!empty($training->modality))
                                    <p class="text-muted small mb-2"><i class="bi bi-laptop me-1"></i>{{ $training->modality }}</p>
                                @endif
                                @if(!empty($course->reference_price))
                                    <p class="text-muted small mb-0"><i class="bi bi-cash-stack me-1"></i>{{ $course->reference_price }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="border rounded-4 p-4 h-100">
                                <h2 class="h5 fw-bold text-dark mb-3">Estudiante</h2>
                                <p class="text-muted small mb-2"><strong class="text-dark">Nombre:</strong> {{ $studentName }}</p>
                                @if(!empty($studentEmail))
                                    <p class="text-muted small mb-0"><strong class="text-dark">Correo:</strong> {{ $studentEmail }}</p>
                                @endif

                                <hr class="my-4">

                                <h2 class="h5 fw-bold text-dark mb-3">Resumen</h2>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Curso</span>
                                    <span class="fw-semibold text-dark">{{ $course->title }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Forma de pago</span>
                                    <span class="fw-semibold text-dark">{{ $paymentFormLabel ?? 'Pago al contado' }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Medio de pago</span>
                                    <span class="fw-semibold text-dark">{{ $paymentMethod ?? 'Por seleccionar' }}</span>
                                </div>
                                @if(!empty($course->reference_price))
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                        <span class="text-muted">Total a pagar</span>
                                        <span class="fw-bold text-dark">{{ $course->reference_price }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4" role="alert">
                        La inscripción definitiva se gestionará por WhatsApp. Contáctanos para finalizar tu matrícula.
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
