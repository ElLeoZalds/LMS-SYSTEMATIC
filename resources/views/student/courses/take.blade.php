@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">{{ $assessment->title }}</h1>
                <p class="text-muted mb-0">Responde todas las preguntas antes de que se agote el tiempo.</p>
            </div>
            <div class="text-end">
                <div class="card bg-light border-0 p-3">
                    <div class="text-center">
                        <small class="d-block text-muted mb-1">Tiempo restante</small>
                        <div id="timer" class="h4 fw-bold text-primary mb-0">
                            @php
                                // Calcular remainingSeconds desde los valores enviados por el servidor
                                $serverNowTs = $serverNowTs ?? null;
                                $attemptCreatedTs = $attemptCreatedTs ?? null;
                                $totalSeconds = $totalSeconds ?? 3600;
                                if ($serverNowTs && $attemptCreatedTs) {
                                    $computed = max(0, intval($totalSeconds) - (intval($serverNowTs) - intval($attemptCreatedTs)));
                                } else {
                                    $computed = intval($totalSeconds);
                                }
                                $initMin = floor($computed / 60);
                                $initSec = $computed % 60;
                            @endphp
                            <span id="minutes">{{ str_pad($initMin, 2, '0', STR_PAD_LEFT) }}</span>:<span id="seconds">{{ str_pad($initSec, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="examForm" action="{{ route('student.assessment.submit', $assessment->assessment_id) }}" method="POST" class="mb-4">
            @csrf
            <input type="hidden" name="attempt_id" value="{{ $attempt->attempt_id }}">

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($assessment->description)
                        <div class="alert alert-info mb-4" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            {{ $assessment->description }}
                        </div>
                    @endif

                    @forelse($assessment->questions as $question)
                        <div class="mb-4 pb-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h6 class="fw-bold text-dark">
                                    <span class="badge bg-primary me-2">{{ $loop->iteration }}</span>
                                    {{ $question->question_text }}
                                </h6>
                                <span class="badge bg-success">{{ $question->score }} pts</span>
                            </div>

                            <div class="mt-2">
                                {{-- Cambiado de $question->options a $question->alternatives --}}
                                @foreach($question->alternatives as $alternative)
                                    <div class="form-check mb-2">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            id="option_{{ $alternative->option_id }}"
                                            name="answers[{{ $question->question_id }}]"
                                            value="{{ $alternative->option_id }}"
                                        >
                                        <label class="form-check-label" for="option_{{ $alternative->option_id }}">
                                            {{ $alternative->option_text }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning mb-0" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            No hay preguntas disponibles para esta evaluación.
                        </div>
                    @endforelse
                </div>
            </div>

            @if($assessment->questions->isNotEmpty())
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                        <i class="bi bi-check-circle me-2"></i>Enviar evaluación
                    </button>
                    <a href="{{ route('student.courses.show', $assessment->training_id) }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </a>
                </div>
            @endif
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para cálculo del temporizador sin depender del reloj del servidor
            const totalSeconds = {{ $totalSeconds }};
            const attemptCreatedTs = {{ $attemptCreatedTs }}; // en segundos epoch
            const serverNowTs = {{ $serverNowTs }}; // en segundos epoch

            let isSubmitting = false;
            const minutesSpan = document.getElementById('minutes');
            const secondsSpan = document.getElementById('seconds');
            const timerDiv = document.getElementById('timer');
            const examForm = document.getElementById('examForm');

            examForm.addEventListener('submit', function() {
                isSubmitting = true;
            });

            // Calcular offset entre servidor y cliente para obtener tiempo del servidor en el cliente
            const clientNow = Date.now() / 1000;
            const offset = serverNowTs - clientNow; // serverTime = Date.now()/1000 + offset

            function computeRemaining() {
                const nowServer = Date.now() / 1000 + offset;
                const elapsed = Math.floor(nowServer - attemptCreatedTs);
                return Math.max(0, totalSeconds - elapsed);
            }

            function renderTimer(remaining) {
                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                minutesSpan.textContent = String(minutes).padStart(2, '0');
                secondsSpan.textContent = String(seconds).padStart(2, '0');

                if (remaining <= 300) { // Menos de 5 minutos
                    timerDiv.classList.remove('text-primary');
                    timerDiv.classList.add('text-danger');
                } else {
                    timerDiv.classList.remove('text-danger');
                    timerDiv.classList.add('text-primary');
                }
            }

            // Evitar diálogo nativo beforeunload (el usuario pidió quitarlo)
            // No añadimos listener a beforeunload.

            // Inicializar mostrando el tiempo calculado
            let remainingSeconds = computeRemaining();
            renderTimer(remainingSeconds);

            // Iniciar contador que recalcula cada segundo usando el offset (esto evita saltos)
            const timerInterval = setInterval(() => {
                remainingSeconds = computeRemaining();
                renderTimer(remainingSeconds);
                if (remainingSeconds <= 0) {
                    clearInterval(timerInterval);
                    if (!isSubmitting) {
                        isSubmitting = true;
                        Swal.fire({
                            icon: 'info',
                            title: 'Tiempo agotado',
                            text: 'El tiempo ha terminado. Tu evaluación será enviada automáticamente.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                examForm.submit();
                            }
                        });
                    }
                }
            }, 1000);

            // Cuando el usuario hace clic en el enlace 'Cancelar', mostrar SweetAlert informando que el tiempo sigue corriendo
            const cancelLink = document.querySelector('a[href="{{ route('student.courses.show', $assessment->training_id) }}"]');
            if (cancelLink) {
                cancelLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Salir del examen',
                        text: 'Si sales, el tiempo seguirá corriendo. ¿Deseas continuar?',
                        showCancelButton: true,
                        confirmButtonText: 'Salir',
                        cancelButtonText: 'Permanecer'
                    }).then((res) => {
                        if (res.isConfirmed) {
                            window.location.href = cancelLink.href;
                        }
                    });
                });
            }
        });
    </script>
@endsection