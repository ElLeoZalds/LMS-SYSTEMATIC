@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <x-page-header
            title="Editar Curso"
            subtitle="Actualiza los datos del curso sin perder su configuración actual."
            action-route="{{ route('admin.courses.index') }}"
            action-label="Volver al listado"
            action-icon="arrow-left"
        />

        <div class="row g-4">
            <div class="col-md-6 order-1">
                <div class="card shadow h-100 border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Detalles del Curso</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.courses.update', $course) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="title">Título</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $course->title) }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $course->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="specialty_id">Especialidad</label>
                                <select name="specialty_id" id="specialty_id" class="form-control @error('specialty_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar especialidad</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty->specialty_id }}" {{ old('specialty_id', $course->specialty_id) == $specialty->specialty_id ? 'selected' : '' }}>
                                            {{ $specialty->specialty }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialty_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="hours_count">Horas</label>
                                <input type="number" name="hours_count" id="hours_count" class="form-control @error('hours_count') is-invalid @enderror" value="{{ old('hours_count', $course->hours_count) }}" min="1" step="1">
                                @error('hours_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="reference_price">Precio de referencia</label>
                                <input type="number" name="reference_price" id="reference_price" class="form-control @error('reference_price') is-invalid @enderror" value="{{ old('reference_price', $course->reference_price) }}" min="0" step="0.01" inputmode="decimal">
                                @error('reference_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="banner_path">Ruta del banner</label>
                                <input type="text" name="banner_path" id="banner_path" class="form-control @error('banner_path') is-invalid @enderror" value="{{ old('banner_path', $course->banner_path) }}">
                                @error('banner_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4 d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 order-2">
                <div class="card shadow h-100 border-0">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i> Módulos del Curso</h5>
                                <p class="text-muted small mb-0">Gestiona el contenido estructural del curso desde esta misma vista.</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#moduleModal">
                                <i class="fas fa-plus"></i> Agregar Módulo
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($modules->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Descripción</th>
                                            <th>Orden</th>
                                            <th>Estado</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($modules as $module)
                                            <tr>
                                                <td class="align-middle fw-bold">{{ $module->title }}</td>
                                                <td class="align-middle text-muted small">{{ Illuminate\Support\Str::limit(strip_tags($module->description ?? ''), 70) ?: 'Sin descripción' }}</td>
                                                <td class="align-middle">{{ $module->order }}</td>
                                                <td class="align-middle">
                                                    <span class="badge {{ $module->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $module->is_active ? 'Activo' : 'Inactivo' }}
                                                    </span>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <button type="button" class="btn btn-sm btn-warning me-1" data-toggle="modal" data-target="#moduleModal" data-mode="edit" data-module-id="{{ $module->id }}" data-title="{{ $module->title }}" data-description="{{ $module->description }}" data-order="{{ $module->order }}" data-active="{{ $module->is_active ? '1' : '0' }}" data-edit-url="{{ route('admin.courses.modules.update', ['course' => $course->course_id, 'module' => $module->id]) }}">
                                                        <i class="fas fa-edit"></i> Editar
                                                    </button>
                                                    <form action="{{ route('admin.courses.modules.toggle-active', ['course' => $course->course_id, 'module' => $module->id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm {{ $module->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                                            <i class="fas {{ $module->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i> {{ $module->is_active ? 'Desactivar' : 'Activar' }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-light border text-center mb-0">
                                <p class="mb-3">Este curso aún no tiene módulos. Comienza agregando el primero.</p>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#moduleModal">
                                    <i class="fas fa-plus"></i> Agregar Módulo
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="moduleModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="moduleForm" method="POST">
                        @csrf
                        <input type="hidden" name="module_id" id="module_id" value="">
                        <div class="modal-header">
                            <h5 class="modal-title" id="moduleModalLabel">Agregar módulo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="module_title">Título</label>
                                <input type="text" name="title" id="module_title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="module_description">Descripción</label>
                                <textarea name="description" id="module_description" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="module_order">Orden</label>
                                <input type="number" name="order" id="module_order" class="form-control" min="0" value="0">
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" name="is_active" id="module_is_active" class="form-check-input" value="1" checked>
                                <label class="form-check-label" for="module_is_active">Módulo activo</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar módulo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('moduleModal');
            const form = document.getElementById('moduleForm');
            const modalTitle = document.getElementById('moduleModalLabel');
            const moduleIdInput = document.getElementById('module_id');
            const titleInput = document.getElementById('module_title');
            const descriptionInput = document.getElementById('module_description');
            const orderInput = document.getElementById('module_order');
            const activeInput = document.getElementById('module_is_active');

            $('#moduleModal').on('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const mode = button.getAttribute('data-mode') || 'create';

                const existingMethod = form.querySelector('input[name="_method"]');
                if (existingMethod) {
                    existingMethod.remove();
                }

                if (mode === 'edit') {
                    modalTitle.textContent = 'Editar módulo';
                    moduleIdInput.value = button.getAttribute('data-module-id') || '';
                    titleInput.value = button.getAttribute('data-title') || '';
                    descriptionInput.value = button.getAttribute('data-description') || '';
                    orderInput.value = button.getAttribute('data-order') || '0';
                    activeInput.checked = button.getAttribute('data-active') === '1';
                    form.action = button.getAttribute('data-edit-url');
                    form.method = 'POST';
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PATCH';
                    form.appendChild(methodInput);
                } else {
                    modalTitle.textContent = 'Agregar módulo';
                    moduleIdInput.value = '';
                    titleInput.value = '';
                    descriptionInput.value = '';
                    orderInput.value = '0';
                    activeInput.checked = true;
                    form.action = '{{ route('admin.courses.modules.store', $course->course_id) }}';
                    form.method = 'POST';
                }
            });
        });
    </script>
@endpush
