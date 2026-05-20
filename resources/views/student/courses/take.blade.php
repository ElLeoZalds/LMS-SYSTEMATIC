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
                            <span id="minutes">60</span>:<span id="seconds">00</span>
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
            // Tiempo límite en minutos (por defecto 60)
            let timeLimit = {{ $timeLimit }};
            let totalSeconds = timeLimit * 60;
            let remainingSeconds = totalSeconds;
            let isSubmitting = false;

            const minutesSpan = document.getElementById('minutes');
            const secondsSpan = document.getElementById('seconds');
            const timerDiv = document.getElementById('timer');
            const examForm = document.getElementById('examForm');

            examForm.addEventListener('submit', function() {
                isSubmitting = true;
            });

            function updateTimer() {
                const minutes = Math.floor(remainingSeconds / 60);
                const seconds = remainingSeconds % 60;

                minutesSpan.textContent = String(minutes).padStart(2, '0');
                secondsSpan.textContent = String(seconds).padStart(2, '0');

                // Cambiar color según tiempo restante
                if (remainingSeconds <= 300) { // Menos de 5 minutos
                    timerDiv.classList.remove('text-primary');
                    timerDiv.classList.add('text-danger');
                } else {
                    timerDiv.classList.remove('text-danger');
                    timerDiv.classList.add('text-primary');
                }

                // Si el tiempo se agota, enviar el formulario
                if (remainingSeconds <= 0) {
                    clearInterval(timerInterval);
                    alert('¡El tiempo ha terminado! Tu evaluación será enviada automáticamente.');
                    isSubmitting = true;
                    examForm.submit();
                } else {
                    remainingSeconds--;
                }
            }

            // Actualizar cada segundo
            const timerInterval = setInterval(updateTimer, 1000);

            // Actualizar una vez al cargar
            updateTimer();

            // Prevenir que el usuario cierre la pestaña sin advertencia
            window.addEventListener('beforeunload', function(e) {
                if (isSubmitting) {
                    return;
                }
                e.preventDefault();
                e.returnValue = '';
            });
        });
    </script>
@endsection