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

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <p class="text-muted small mb-1">Intentos permitidos: {{ $assessment->allowed_attempts }}</p>
                    <p class="text-muted small">Inicio: {{ optional($assessment->start_date)->format('d/m/Y') }} · Fin: {{ optional($assessment->end_date)->format('d/m/Y') }}</p>
                </div>

                <div class="mb-4">
                    <button class="btn btn-sm btn-outline-success add-question-btn" type="button"
                        data-toggle="modal"
                        data-target="#questionModal"
                        data-mode="create"
                        data-action="{{ route('teacher.assessments.questions.store', $assessment->assessment_id) }}">
                        <i class="fas fa-plus-circle mr-1"></i> Nueva Pregunta
                    </button>
                </div>

                @if($assessment->questions->count())
                    @foreach($assessment->questions as $question)
                        <div class="card bg-light mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="font-weight-bold text-gray-800">{{ $question->question_text }}</div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-secondary p-2 mr-2">{{ $question->score }} pts</span>

                                        <button class="btn btn-sm btn-light text-primary edit-question-btn mr-1" type="button"
                                            data-toggle="modal"
                                            data-target="#questionModal"
                                            data-mode="edit"
                                            data-action="{{ route('teacher.questions.update', $question->question_id) }}"
                                            data-question="{{ json_encode([
                                                'text' => $question->question_text,
                                                'score' => $question->score,
                                                'alternatives' => $question->alternatives->map(function($alt) {
                                                    return ['text' => $alt->option_text, 'is_correct' => $alt->is_correct];
                                                })
                                            ]) }}" data-image="{{ $question->image_path ? asset('storage/'.$question->image_path) : '' }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('teacher.questions.destroy', $question->question_id) }}" method="POST" class="d-inline swal-confirm" data-message="¿Estás completamente seguro de eliminar esta pregunta? Esta acción no se puede deshacer.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger" title="Eliminar Pregunta">
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

    @include('teacher.assessments._question_modal')

@endsection
