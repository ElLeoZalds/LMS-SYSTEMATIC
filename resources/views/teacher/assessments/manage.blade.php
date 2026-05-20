@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">Evaluaciones de {{ $training->course->title }}</h1>
                <p class="text-muted">Gestiona las evaluaciones del curso y crea preguntas para cada evaluación.</p>
            </div>
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

        {{-- Tarjetas de Resumen --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow border-left-primary h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Capacitación</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $training->course->title }}</div>
                        <div class="small text-muted mt-1">Código: {{ $training->course->code ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow border-left-success h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Evaluaciones existentes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $training->assessments->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow border-left-info h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Estado del curso</div>
                        <div class="mt-2">
                            <span class="badge badge-success px-3 py-2">Activo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Listado de Evaluaciones y Preguntas --}}
        <div class="row">
            @forelse($training->assessments as $assessment)
                <div class="col-12 mb-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="m-0 font-weight-bold text-primary mb-1">{{ $assessment->title }}</h5>
                                    <p class="text-muted small mb-1">Intentos permitidos: {{ $assessment->allowed_attempts }}</p>
                                    <p class="text-muted small mb-0">Inicio: {{ optional($assessment->start_date)->format('d/m/Y') }} · Fin: {{ optional($assessment->end_date)->format('d/m/Y') }}</p>
                                </div>
                                <div class="text-md-right mt-2 mt-md-0">
                                    <span class="badge badge-{{ $assessment->active ? 'primary' : 'secondary' }} mb-2 d-block d-md-inline-block">
                                        {{ $assessment->active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    
                                    {{-- Botón Nueva Pregunta --}}
                                    <button class="btn btn-sm btn-outline-success add-question-btn d-block mt-1" type="button"
                                        data-toggle="modal"
                                        data-target="#questionModal"
                                        data-mode="create"
                                        data-action="{{ route('teacher.assessments.questions.store', $assessment->assessment_id) }}">
                                        <i class="fas fa-plus-circle mr-1"></i> Nueva Pregunta
                                    </button>
                                </div>
                            </div>

                            <hr class="my-3">

                            {{-- SECCIÓN DE REPORTE DE NOTAS DE ALUMNOS (CORREGIDA) --}}
                            <div class="mb-4">
                                <button class="btn btn-sm btn-block btn-light text-left border d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#reportScore-{{ $assessment->assessment_id }}" aria-expanded="false" aria-controls="reportScore-{{ $assessment->assessment_id }}">
                                    <span class="font-weight-bold text-gray-700"><i class="fas fa-chart-bar mr-2 text-info"></i> Reporte de Alumnos Evaluados</span>
                                    <i class="fas fa-chevron-down text-muted"></i>
                                </button>
                                
                                <div class="collapse mt-2" id="reportScore-{{ $assessment->assessment_id }}">
                                    <div class="card card-body p-2 bg-white shadow-sm">
                                        {{-- Usamos la relación correcta: attempts --}}
                                        @if($assessment->attempts && $assessment->attempts->count())
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover mb-0 text-dark small">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>Alumno</th>
                                                            <th class="text-center">Fecha de Envío</th>
                                                            <th class="text-right">Calificación</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($assessment->attempts as $attempt)
                                                            <tr>
                                                                <td class="font-weight-bold">{{ $attempt->user->name ?? 'Estudiante' }}</td>
                                                                <td class="text-center">{{ $attempt->created_at ? $attempt->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                                                <td class="text-right font-weight-bold text-{{ $attempt->score >= 11 ? 'success' : 'danger' }}">
                                                                    {{ $attempt->score }} / 20
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="text-muted text-center p-3 small">
                                                <i class="fas fa-info-circle mr-1"></i> Ningún alumno ha rendido esta evaluación todavía.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- INDICADOR DE PUNTAJE MÁXIMO / ACUMULADO --}}
                            @php
                                $totalScore = $assessment->questions->sum('score');
                                $targetScore = 20; 
                            @endphp

                            @if($assessment->questions->count())
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-uppercase text-secondary font-weight-bold small m-0">Preguntas Asignadas</h6>
                                        <span class="badge badge-{{ $totalScore == $targetScore ? 'success' : ($totalScore > $targetScore ? 'danger' : 'warning') }} p-2">
                                            Puntaje total configurado: <strong>{{ $totalScore }} / {{ $targetScore }} pts</strong>
                                        </span>
                                    </div>

                                    @foreach($assessment->questions as $question)
                                        <div class="card bg-light mb-3">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="font-weight-bold text-gray-800">{{ $question->question_text }}</div>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge badge-secondary p-2 mr-2">{{ $question->score }} pts</span>
                                                        
                                                        {{-- Botón para Editar Pregunta --}}
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
                                                            ]) }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        {{-- BOTÓN PARA ELIMINAR PREGUNTA --}}
                                                        <form action="{{ route('teacher.questions.destroy', $question->question_id) }}" method="POST" onsubmit="return confirm('¿Estás completamente seguro de eliminar esta pregunta? Esta acción no se puede deshacer.');" class="d-inline">
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
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-secondary mb-0" role="alert">
                                    <strong>Sin preguntas aún.</strong> Añade la primera pregunta para esta evaluación.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-clipboard-list text-gray-400 mb-3" style="font-size: 3rem;"></i>
                            <h5 class="mt-2 mb-2 text-gray-700">No hay evaluaciones todavía</h5>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- MODAL DE CREACIÓN / EDICIÓN --}}
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold text-gray-800" id="questionModalLabel">Nueva Pregunta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="questionForm" method="POST" action="">
                    @csrf
                    <div id="methodContainer"></div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="question_text" class="text-gray-700 font-weight-bold">Pregunta</label>
                            <input type="text" class="form-control form-control-sm" id="question_text" name="question_text" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="score" class="text-gray-700 font-weight-bold">Puntos</label>
                            <input type="number" class="form-control form-control-sm" id="score" name="score" min="0" required>
                        </div>
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="text-gray-700 font-weight-bold mb-0">Alternativas</label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addAlternativeBtn">+ Añadir Opción</button>
                            </div>
                            <div id="alternativesContainer"></div>
                            <div class="text-muted small mt-2">Marca la alternativa correcta con el círculo.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm">Guardar Pregunta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const questionForm = document.getElementById('questionForm');
                const methodContainer = document.getElementById('methodContainer');
                const modalTitle = document.getElementById('questionModalLabel');
                const alternativesContainer = document.getElementById('alternativesContainer');
                const addAlternativeBtn = document.getElementById('addAlternativeBtn');
                let alternativeIndex = 0;

                function createAlternativeRow(index, textValue = '', isChecked = false) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'd-flex align-items-center mb-2 alternative-row';
                    
                    let radioChecked = isChecked ? 'checked' : '';
                    
                    wrapper.innerHTML = `
                        <div class="input-group input-group-sm flex-grow-1 mr-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_alternative" value="` + index + `" aria-label="Correcta" ` + radioChecked + ` required>
                                </div>
                            </div>
                            <input type="text" name="alternatives[` + index + `][text]" class="form-control form-control-sm" value="` + textValue.replace(/"/g, '&quot;') + `" placeholder="Opción ` + (index + 1) + `" required>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-alternative-btn">Eliminar</button>
                    `;

                    wrapper.querySelector('.remove-alternative-btn').addEventListener('click', function () {
                        wrapper.remove();
                        refreshAlternativeIndexes();
                    });

                    return wrapper;
                }

                function refreshAlternativeIndexes() {
                    let tempIndex = 0;
                    const rows = alternativesContainer.querySelectorAll('.alternative-row');
                    rows.forEach((row) => {
                        const radio = row.querySelector('input[type="radio"]');
                        const textInput = row.querySelector('input[type="text"]');
                        
                        radio.value = tempIndex;
                        textInput.name = 'alternatives[' + tempIndex + '][text]';
                        textInput.placeholder = 'Opción ' + (tempIndex + 1);
                        tempIndex++;
                    });
                    alternativeIndex = tempIndex;
                }

                function addAlternativeRow(textValue = '', isChecked = false) {
                    const row = createAlternativeRow(alternativeIndex, textValue, isChecked);
                    alternativesContainer.appendChild(row);
                    alternativeIndex++;
                }

                if (addAlternativeBtn) {
                    addAlternativeBtn.addEventListener('click', function () {
                        addAlternativeRow();
                    });
                }

                document.addEventListener('click', function (event) {
                    const button = event.target.closest('.add-question-btn, .edit-question-btn');
                    if (!button) return;

                    const mode = button.getAttribute('data-mode');
                    const action = button.getAttribute('data-action');
                    
                    questionForm.action = action;
                    questionForm.reset();
                    alternativesContainer.innerHTML = '';
                    alternativeIndex = 0;

                    if (mode === 'create') {
                        modalTitle.textContent = 'Nueva Pregunta';
                        methodContainer.innerHTML = ''; 
                        addAlternativeRow('', true); 
                        addAlternativeRow('', false);
                    } else if (mode === 'edit') {
                        modalTitle.textContent = 'Editar Pregunta';
                        methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">'; 
                        
                        const questionData = JSON.parse(button.getAttribute('data-question'));
                        document.getElementById('question_text').value = questionData.text;
                        document.getElementById('score').value = questionData.score;

                        if (questionData.alternatives && questionData.alternatives.length > 0) {
                            questionData.alternatives.forEach((alt) => {
                                addAlternativeRow(alt.text, alt.is_correct == 1);
                            });
                        } else {
                            addAlternativeRow('', true);
                            addAlternativeRow('', false);
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection