@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">Evaluaciones de {{ $training->course->title }}</h1>
                <p class="text-muted">Gestiona las evaluaciones del curso y crea preguntas para cada evaluación.</p>
            </div>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createAssessmentModal" data-bs-toggle="modal" data-bs-target="#createAssessmentModal">
                <i class="bi bi-plus-lg me-1"></i> Nueva Evaluación
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Capacitación</h6>
                        <p class="fw-bold mb-1">{{ $training->course->title }}</p>
                        <p class="small text-muted mb-0">Código: {{ $training->course->code ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Evaluaciones existentes</h6>
                        <p class="fw-bold mb-0">{{ $training->assessments->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted">Estado del curso</h6>
                        <span class="badge bg-success">Activo</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($training->assessments as $assessment)
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-3">
                                <div>
                                    <h5 class="card-title mb-1">{{ $assessment->title }}</h5>
                                    <p class="text-muted small mb-1">Intentos permitidos: {{ $assessment->allowed_attempts }}</p>
                                    <p class="text-muted small mb-0">Inicio: {{ optional($assessment->start_date)->format('d/m/Y') }} · Fin: {{ optional($assessment->end_date)->format('d/m/Y') }}</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $assessment->active ? 'primary' : 'secondary' }} mb-2">
                                        {{ $assessment->active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    <button class="btn btn-sm btn-outline-success add-question-btn" type="button"
                                        data-toggle="modal"
                                        data-target="#addQuestionModal"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addQuestionModal"
                                        data-action="{{ route('teacher.assessments.questions.store', $assessment->assessment_id) }}">
                                        <i class="bi bi-plus-circle me-1"></i> Nueva Pregunta
                                    </button>
                                </div>
                            </div>

                            @if($assessment->questions->count())
                                <div class="mb-3">
                                    <h6 class="text-uppercase text-secondary fw-bold small mb-3">Preguntas</h6>
                                    @foreach($assessment->questions as $question)
                                        <div class="bg-light rounded-3 p-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="fw-semibold">{{ $question->question_text }}</div>
                                                <span class="badge bg-secondary">{{ $question->score }} pts</span>
                                            </div>
                                            <div class="list-group list-group-flush">
                                                @foreach($question->options as $option)
                                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0 bg-transparent">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="text-muted">{{ $loop->iteration }}.</span>
                                                            <span>{{ $option->option_text }}</span>
                                                        </div>
                                                        @if($option->is_correct)
                                                            <span class="badge bg-success">Correcta</span>
                                                        @endif
                                                    </div>
                                                @endforeach
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
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-clipboard-minus" style="font-size: 3rem;"></i>
                            <h5 class="mt-4 mb-2">No hay evaluaciones todavía</h5>
                            <p class="text-muted">Usa el botón "Nueva Evaluación" para crear la primera.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="modal fade" id="createAssessmentModal" tabindex="-1" aria-labelledby="createAssessmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAssessmentModalLabel">Nueva Evaluación</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('teacher.assessments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Título</label>
                            <input type="text" class="form-control form-control-sm" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Fecha de inicio</label>
                                <input type="date" class="form-control form-control-sm" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">Fecha de fin</label>
                                <input type="date" class="form-control form-control-sm" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="allowed_attempts" class="form-label">Intentos permitidos</label>
                            <input type="number" class="form-control form-control-sm" id="allowed_attempts" name="allowed_attempts" value="{{ old('allowed_attempts', 1) }}" min="1" required>
                            @error('allowed_attempts') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ old('active') ? 'checked' : '' }}>
                            <label class="form-check-label" for="active">Activo</label>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción (opcional)</label>
                            <textarea class="form-control form-control-sm" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm">Guardar Evaluación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addQuestionModalLabel">Nueva Pregunta</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addQuestionForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="question_text" class="form-label">Pregunta</label>
                            <input type="text" class="form-control form-control-sm" id="question_text" name="question_text" required>
                        </div>
                        <div class="mb-3">
                            <label for="score" class="form-label">Puntos</label>
                            <input type="number" class="form-control form-control-sm" id="score" name="score" min="0" required>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Alternativas</label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addOptionBtn">+ Añadir Opción</button>
                            </div>
                            <div id="optionsContainer"></div>
                            <div class="text-muted small">Marca la alternativa correcta con el círculo.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm">Guardar Pregunta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addQuestionForm = document.getElementById('addQuestionForm');
            const optionsContainer = document.getElementById('optionsContainer');
            const addOptionBtn = document.getElementById('addOptionBtn');
            let optionIndex = 0;

            function createOptionRow(index) {
                const wrapper = document.createElement('div');
                wrapper.className = 'd-flex align-items-center gap-2 mb-2 option-row';
                wrapper.innerHTML = `
                    <div class="input-group input-group-sm flex-grow-1">
                        <span class="input-group-text p-1">
                            <input class="form-check-input mt-0" type="radio" name="correct_option" value="${index}" aria-label="Correcta">
                        </span>
                        <input type="text" name="options[${index}][text]" class="form-control form-control-sm" placeholder="Opción ${index + 1}" required>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-option-btn">Eliminar</button>
                `;

                wrapper.querySelector('.remove-option-btn').addEventListener('click', function () {
                    wrapper.remove();
                    refreshOptionIndexes();
                });

                return wrapper;
            }

            function refreshOptionIndexes() {
                optionIndex = 0;
                optionsContainer.querySelectorAll('.option-row').forEach((row) => {
                    row.querySelector('input[type="radio"]').value = optionIndex;
                    row.querySelector('input[type="text"]').name = `options[${optionIndex}][text]`;
                    row.querySelector('input[type="text"]').placeholder = `Opción ${optionIndex + 1}`;
                    optionIndex++;
                });
            }

            function resetOptions() {
                optionsContainer.innerHTML = '';
                optionIndex = 0;
                addOptionRow();
                addOptionRow();
            }

            function addOptionRow() {
                const row = createOptionRow(optionIndex);
                optionsContainer.appendChild(row);
                optionIndex++;
            }

            addOptionBtn.addEventListener('click', function () {
                addOptionRow();
            });

            document.querySelectorAll('.add-question-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    const action = this.dataset.action;
                    addQuestionForm.action = action;
                    addQuestionForm.reset();
                    resetOptions();
                });
            });

            // Initialize default options if modal exists
            if (optionsContainer) {
                resetOptions();
            }

            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                });
            @endif
        });
    </script>
@endpush
