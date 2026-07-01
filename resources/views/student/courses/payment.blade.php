@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="mb-4">
                        <h1 class="h3 fw-bold text-dark mb-2">Selección de pago</h1>
                        <p class="text-muted mb-0">Revisa el curso y elige la forma y el medio de pago antes de continuar.</p>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-5">
                            <div class="border rounded-4 p-4 h-100">
                                <h2 class="h5 fw-bold text-dark mb-3">Resumen del curso</h2>
                                <h3 class="h6 fw-bold text-dark mb-2">{{ $course->title }}</h3>
                                <p class="text-muted small mb-2">{{ optional($course->specialty)->specialty }}</p>
                                @if(!empty($training->modality))
                                    <p class="text-muted small mb-2"><i class="bi bi-laptop me-1"></i>{{ $training->modality }}</p>
                                @endif
                                @if(!empty($course->hours_count))
                                    <p class="text-muted small mb-2"><i class="bi bi-clock-history me-1"></i>{{ $course->hours_count }} h</p>
                                @endif
                                @if(!empty($course->reference_price))
                                    <p class="text-muted small mb-0"><i class="bi bi-cash-stack me-1"></i>{{ $course->reference_price }}</p>
                                @endif

                                <hr class="my-4">

                                <h2 class="h5 fw-bold text-dark mb-3">Resumen del estudiante</h2>
                                <p class="text-muted small mb-2"><strong class="text-dark">Nombre:</strong> {{ $studentName }}</p>
                                @if(!empty($studentEmail))
                                    <p class="text-muted small mb-0"><strong class="text-dark">Correo:</strong> {{ $studentEmail }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="border rounded-4 p-4 h-100">
                                <h2 class="h5 fw-bold text-dark mb-3">Forma de pago</h2>

                                <div class="list-group">
                                    <label class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold text-dark">Pago al contado</div>
                                            <div class="small text-muted">
                                                @if(!empty($course->reference_price))
                                                    <div>Precio original: {{ $course->reference_price }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <input class="form-check-input mt-1" type="radio" name="payment_form" value="contado" disabled>
                                    </label>

                                    <label class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold text-dark">Pago en cuotas</div>
                                            <div class="small text-muted">
                                                <div>Disponible próximamente.</div>
                                            </div>
                                        </div>
                                        <input class="form-check-input mt-1" type="radio" name="payment_form" value="cuotas" disabled>
                                    </label>
                                </div>

                                <hr class="my-4">

                                <h2 class="h5 fw-bold text-dark mb-3">Medio de pago</h2>
                                @if($paymentMethods->isNotEmpty())
                                    <div class="list-group">
                                        @foreach($paymentMethods as $paymentMethod)
                                            <label class="list-group-item d-flex justify-content-between align-items-center">
                                                <span class="text-dark">{{ $paymentMethod->payment_method }}</span>
                                                <input class="form-check-input" type="radio" name="payment_method" value="{{ $paymentMethod->method_id }}" disabled>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-muted small">No hay métodos de pago registrados.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4" role="alert">
                        El pago y la inscripción se realizarán ahora por WhatsApp. Contáctanos para recibir asistencia personalizada.
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
