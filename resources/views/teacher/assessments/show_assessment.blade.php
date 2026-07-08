@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">{{ $assessment->title }}</h1>
                <p class="text-muted">Gestiona las preguntas de esta evaluación.</p>
            </div>
            <a href="{{ route('teacher.courses.show', $assessment->training->training_id) }}?tab=contenido" class="btn btn-sm btn-outline-secondary">Volver al Curso</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                @php
                    $totalScore = $assessment->questions->sum('score');
                    $hasAttempts = $assessment->attempts()->whereNotNull('submitted_at')->exists();
                @endphp
                @if($hasAttempts)
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-lock me-2"></i>Esta evaluación ya tiene intentos de estudiantes. No se puede modificar su estructura ni sus preguntas.
                    </div>
                @endif
                <div class="mb-3">
                    <p class="text-muted small mb-1">Intentos permitidos: {{ $assessment->allowed_attempts }}</p>
                    <p class="text-muted small">Inicio: {{ optional($assessment->start_date)->format('d/m/Y') }} · Fin: {{ optional($assessment->end_date)->format('d/m/Y') }}</p>
                    <p class="text-muted small mb-0">Puntaje total configurado: {{ $totalScore }} / 20 pts</p>
                </div>

                <div class="mb-4">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <button type="button" class="btn btn-sm btn-primary save-assessment-btn" @if($hasAttempts) disabled @endif>
                            <i class="fas fa-save mr-1"></i> Guardar Evaluación
                        </button>
                        <button class="btn btn-sm btn-outline-success add-question-btn" type="button"
                            data-toggle="modal"
                            data-target="#questionModal"
                            data-mode="create"
                            data-action="{{ route('teacher.assessments.questions.store', $assessment->assessment_id) }}"
                            @if($hasAttempts) disabled title="Esta evaluación ya tiene intentos de estudiantes y no puede modificarse" @endif>
                            <i class="fas fa-plus-circle mr-1"></i> Nueva Pregunta
                        </button>
                    </div>
                </div>

                @if($assessment->questions->count())
                    @foreach($assessment->questions as $question)
                        <div class="card bg-light mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="font-weight-bold text-gray-800">{{ $question->question_text }}</div>
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('teacher.questions.score.update', $question->question_id) }}" method="POST" class="d-flex align-items-center mr-2 question-score-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="score" value="{{ $question->score }}" min="0" max="20" step="1" class="form-control form-control-sm text-center mr-1 question-score-input" style="width: 72px;" @if($hasAttempts) disabled title="No se pueden cambiar los puntos una vez que la evaluación ya tiene intentos" @endif>
                                            <span class="text-muted small mr-2">pts</span>
                                        </form>

                                        <button class="btn btn-sm btn-light text-primary edit-question-btn mr-1" type="button"
                                            data-toggle="modal"
                                            data-target="#questionModal"
                                            data-mode="edit"
                                            data-action="{{ route('teacher.questions.update', $question->question_id) }}"
                                            data-question="{{ json_encode([
                                                'text' => $question->question_text,
                                                'alternatives' => $question->alternatives->map(function($alt) {
                                                    return ['text' => $alt->option_text, 'is_correct' => $alt->is_correct];
                                                })
                                            ]) }}" data-image="{{ $question->image_path ? asset('storage/'.$question->image_path) : '' }}"
                                            @if($hasAttempts) disabled title="Esta evaluación ya tiene intentos de estudiantes y no puede modificarse" @endif>
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('teacher.questions.destroy', $question->question_id) }}" method="POST" class="d-inline swal-confirm" data-message="¿Estás completamente seguro de eliminar esta pregunta? Esta acción no se puede deshacer.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Eliminar Pregunta" @if($hasAttempts) disabled @endif>
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="list-group list-group-flush bg-transparent">
                                    @foreach($question->alternatives as $alternative)
                                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0 bg-transparent">
                                            <div>
                                                <span class="text-muted mr-2">{{ $loop->iteration }}.</span>
                                                <span class="text-gray-700">{{ $alternative->option_text }}</span>
                                            </div>
                                            @if($alternative->is_correct)
                                                <span class="badge badge-success px-2 py-1">Correcta</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if(!empty($question->image_path))
                                    <div class="mt-3 text-center">
                                        <img src="{{ asset('storage/'.$question->image_path) }}" alt="Imagen pregunta" style="max-width: 100%; max-height: 300px; object-fit: contain;" class="img-fluid rounded">
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-secondary mb-0">No hay preguntas aún.</div>
                @endif

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scoreForms = Array.from(document.querySelectorAll('.question-score-form'));
            const saveAssessmentButton = document.querySelector('.save-assessment-btn');

            scoreForms.forEach(function (form) {
                const input = form.querySelector('.question-score-input');
                let saveTimer = null;

                function submitScore() {
                    form.requestSubmit();
                }

                if (input) {
                    input.addEventListener('input', function () {
                        clearTimeout(saveTimer);
                        saveTimer = setTimeout(submitScore, 700);
                    });

                    input.addEventListener('change', submitScore);
                }
            });

            if (saveAssessmentButton) {
                saveAssessmentButton.addEventListener('click', function () {
                    scoreForms.forEach(function (form) {
                        form.requestSubmit();
                    });
                });
            }
        });
    </script>

    @include('teacher.assessments._question_modal')

@endsection
