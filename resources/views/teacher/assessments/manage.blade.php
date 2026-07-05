@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Gestión de Evaluaciones</h2>
            <p class="text-muted mb-0">{{ optional($training->course)->title ?? optional($training->course)->name ?? 'Sin curso' }} - {{ $training->code ?? 'Sin código' }}</p>
        </div>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createAssessmentModal">
            <i class="bi bi-plus-circle me-2"></i>Nueva Evaluación
        </button>
    </div>

    <div class="accordion" id="modulesAccordion">
        @forelse($modules as $index => $module)
            @php
                $moduleStatus = $module->module_status ?? 'Pendiente';
                $moduleStatusClass = $moduleStatus === 'En curso' ? 'success' : ($moduleStatus === 'Completado' ? 'secondary' : 'info');
                $moduleBorderClass = $moduleStatus === 'En curso' ? 'success' : ($moduleStatus === 'Completado' ? 'secondary' : 'info');
            @endphp

            <div class="card mb-3 border-left-{{ $moduleBorderClass }}" style="border-left-width: 4px; border-left-style: solid;">
                <div class="card-header bg-white p-3" id="heading{{ $module->module_id }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-journal-bookmark-fill me-3 text-primary"></i>
                            <div>
                                <h5 class="mb-1">Módulo {{ $module->order }}: {{ $module->name }}</h5>
                                <small class="text-muted">
                                    <span class="badge badge-{{ $moduleStatusClass }} me-2">{{ $moduleStatus }}</span>
                                    {{ $module->assessments_count ?? 0 }} evaluación(es)
                                </small>
                            </div>
                        </div>
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $module->module_id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                            <i class="bi bi-chevron-{{ $index === 0 ? 'up' : 'down' }}"></i>
                        </button>
                    </div>
                </div>

                <div id="collapse{{ $module->module_id }}" class="collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $module->module_id }}" data-parent="#modulesAccordion">
                    <div class="card-body">
                        @if(isset($module->assessments) && $module->assessments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Título</th>
                                            <th>Fecha límite</th>
                                            <th>Intentos</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($module->assessments as $assessment)
                                            <tr>
                                                <td>
                                                    <strong>{{ $assessment->title }}</strong>
                                                    @if($assessment->description)
                                                        <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($assessment->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ optional($assessment->end_date)->format('d/m/Y H:i') ?? 'Sin fecha' }}</td>
                                                <td>{{ $assessment->allowed_attempts ?? 'Ilimitados' }}</td>
                                                <td>
                                                    @if($assessment->active)
                                                        <span class="badge badge-success">Activa</span>
                                                    @else
                                                        <span class="badge badge-secondary">Inactiva</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info text-white" data-toggle="modal" data-target="#editAssessmentModal-{{ $assessment->assessment_id }}">
                                                        <i class="bi bi-pencil"></i> Editar
                                                    </button>
                                                    <a class="btn btn-sm btn-primary" href="{{ route('teacher.assessments.show', ['assessment_id' => $assessment->assessment_id]) }}">
                                                        <i class="bi bi-question-circle"></i> Preguntas
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-journal-x display-4 text-muted"></i>
                                <p class="text-muted mt-2 mb-0">No hay evaluaciones en este módulo</p>
                            </div>
                        @endif

                        <button class="btn btn-sm btn-outline-primary mt-3" onclick="openCreateModal({{ $module->module_id }})">
                            <i class="bi bi-plus-circle"></i> Agregar Evaluación
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>No hay módulos disponibles para esta capacitación.
            </div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="createAssessmentModal" tabindex="-1" role="dialog" aria-labelledby="createAssessmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAssessmentModalLabel">Nueva Evaluación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('teacher.assessments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="training_id" value="{{ $training->training_id }}">
                <input type="hidden" name="module_id" id="modal_module_id" value="">

                <div class="modal-body">
                    <div class="form-group">
                        <label>Título *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de inicio *</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de fin *</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Intentos máximos</label>
                                <input type="number" name="allowed_attempts" class="form-control" min="1" max="3" value="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Límite (min)</label>
                                <input type="number" name="time_limit" class="form-control" min="20" max="60" value="60">
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-check mt-3">
                        <input type="checkbox" name="active" class="form-check-input" checked>
                        <label class="form-check-label">Evaluación activa</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Evaluación</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($modules as $module)
    @foreach($module->assessments as $assessment)
        <div class="modal fade" id="editAssessmentModal-{{ $assessment->assessment_id }}" tabindex="-1" role="dialog" aria-labelledby="editAssessmentModalLabel-{{ $assessment->assessment_id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAssessmentModalLabel-{{ $assessment->assessment_id }}">Editar Evaluación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('teacher.assessments.update', ['assessment_id' => $assessment->assessment_id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="module_id" value="{{ $assessment->module_id }}">
                        <input type="hidden" name="training_id" value="{{ $training->training_id }}">

                        <div class="modal-body">
                            <div class="form-group">
                                <label>Título *</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $assessment->title) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $assessment->description) }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha de inicio *</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($assessment->start_date)->format('Y-m-d')) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha de fin *</label>
                                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($assessment->end_date)->format('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Intentos máximos</label>
                                        <input type="number" name="allowed_attempts" class="form-control" min="1" max="3" value="{{ old('allowed_attempts', $assessment->allowed_attempts) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Límite (min)</label>
                                        <input type="number" name="time_limit" class="form-control" min="20" max="60" value="{{ old('time_limit', $assessment->time_limit) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-check mt-3">
                                <input type="checkbox" name="active" class="form-check-input" {{ old('active', $assessment->active) ? 'checked' : '' }}>
                                <label class="form-check-label">Evaluación activa</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endforeach
@endsection

@push('scripts')
<script>
function openCreateModal(moduleId) {
    $('#modal_module_id').val(moduleId);
    $('#createAssessmentModal').modal('show');
}
</script>
@endpush
