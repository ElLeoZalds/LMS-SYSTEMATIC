@extends('layouts.app')

@section('content')

    <div class="container px-4 py-1">

        <div class="mb-4">
            <h1 class="h3 text-gray-800 mb-1">Inscribir Estudiante en Capacitación</h1>
            <p class="text-muted small">Registra un alumno en la capacitación actual del curso. Los estudiantes ya inscritos en otra capacitación del mismo curso no se muestran.</p>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.enrollments.store') }}">
                            @csrf

                            @if(isset($selectedTraining))
                                <input type="hidden" name="training_id" value="{{ $selectedTraining->training_id }}">
                                <div class="mb-4 p-3 border rounded bg-light">
                                    <div class="fw-bold mb-1">Capacitación seleccionada</div>
                                    <div>{{ optional($selectedTraining->course)->title ?? 'Sin curso' }}{{ optional($selectedTraining->start_date)->format(' (Y-m)') }}</div>
                                    <div class="text-muted small">
                                        {{ $selectedTraining->teacher->person->first_names ?? 'Sin docente' }}
                                        {{ $selectedTraining->teacher->person->last_names ?? '' }}
                                        · {{ $selectedTraining->start_date?->format('d/m/Y') ?? 'Sin fecha' }}
                                        · {{ ucfirst($selectedTraining->modality) }}
                                    </div>
                                </div>
                            @endif

                            {{-- Selector de Estudiantes --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Estudiantes</label>
                                <input type="text" id="studentSearch" class="form-control mb-3" placeholder="Buscar estudiante...">

                                <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                    @forelse($students as $student)
                                        @php
                                            $studentName = trim(($student->person->first_names ?? 'Sin nombre') . ' ' . ($student->person->last_names ?? ''));
                                            $checked = is_array(old('student_ids')) && in_array($student->user_id, old('student_ids'));
                                        @endphp
                                        @php
                                            $isTeacherOfSelectedTraining = isset($selectedTraining) && $selectedTraining->teacher_id == $student->user_id;
                                        @endphp
                                        <div class="form-check student-checkbox mb-2" data-student-name="{{ strtolower($studentName . ' ' . $student->username) }}">
                                            <input class="form-check-input" type="checkbox" name="student_ids[]" value="{{ $student->user_id }}" id="student_{{ $student->user_id }}" @checked($checked) @disabled($isTeacherOfSelectedTraining)>
                                            <label class="form-check-label" for="student_{{ $student->user_id }}">
                                                {{ $studentName }} ({{ $student->username }})
                                            </label>
                                            @if($isTeacherOfSelectedTraining)
                                                <div class="alert alert-warning py-2 px-3 mt-2 mb-0 small">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Este usuario es el profesor de la capacitación y no puede matricularse como estudiante.
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="text-muted small">
                                            No hay estudiantes disponibles para esta capacitación porque ya están inscritos en otra capacitación del mismo curso.
                                        </div>
                                    @endforelse
                                </div>

                                @error('student_ids')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('student_ids.*')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            @if(!isset($selectedTraining))
                                {{-- Selector de Curso --}}
                                <div class="mb-4">
                                    <label for="training_id" class="form-label fw-bold">Capacitación</label>
                                    <select name="training_id" id="training_id" class="form-select @error('training_id') is-invalid @enderror" required>
                                        <option value="">-- Selecciona una capacitación --</option>
                                        @foreach($trainings as $training)
                                            <option value="{{ $training->training_id }}" @selected(old('training_id') == $training->training_id)>
                                                {{ optional($training->course)->title ?? 'Sin curso' }}{{ optional($training->start_date)->format(' (Y-m)') }}
                                                - {{ $training->teacher->person->first_names ?? 'Sin docente' }}
                                                ({{ $training->start_date?->format('d/m/Y') ?? 'Sin fecha' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted d-block mt-2">
                                        <i class="bi bi-info-circle"></i> Selecciona la capacitación en la cual deseas inscribir al estudiante. Solo se muestran capacitaciones activas.
                                    </small>
                                    @error('training_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            {{-- Botones de acción --}}
                            <div class="d-flex gap-2 mt-5">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Inscribir Estudiante
                                </button>
                                <a href="{{ route('admin.trainings.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            {{-- Panel de información --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">
                            <i class="bi bi-lightbulb"></i> Instrucciones
                        </h5>
                        <ul class="small list-unstyled">
                            <li class="mb-2">
                                <strong>1. Selecciona el estudiante:</strong> Elige el alumno de la lista disponible.
                            </li>
                            <li class="mb-2">
                                <strong>2. Confirma la capacitación:</strong> El sistema usa la capacitación actual del curso cuando llegas desde el listado.
                            </li>
                            <li class="mb-2">
                                <strong>3. Regla de exclusión:</strong> Si un alumno ya está inscrito en otra capacitación del mismo curso, no aparecerá en esta lista.
                            </li>
                            <li class="mb-2">
                                <strong>4. Confirma la inscripción:</strong> Haz clic en "Inscribir Estudiante" para completar el proceso.
                            </li>
                            <li class="mb-2 text-danger">
                                <strong>⚠️ Nota:</strong> No es posible inscribir dos veces al mismo estudiante en la misma capacitación.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('studentSearch');
                const studentRows = Array.from(document.querySelectorAll('.student-checkbox'));

                if (!searchInput) {
                    return;
                }

                searchInput.addEventListener('input', function () {
                    const term = this.value.trim().toLowerCase();

                    studentRows.forEach(function (row) {
                        const name = row.dataset.studentName;
                        const isVisible = !term || name.includes(term);
                        row.style.display = isVisible ? 'block' : 'none';
                    });
                });
            });
        </script>
    @endpush

@endsection
