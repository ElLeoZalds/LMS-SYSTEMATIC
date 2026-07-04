@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <x-page-header
            title="Crear Curso"
            subtitle="Completa los datos del curso para habilitarlo en el panel de administración."
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
                        <form id="courseForm" method="POST" action="{{ route('admin.courses.store') }}">
                            @csrf

                            <input type="hidden" name="modules_data" id="modulesData" value="">

                            <div class="form-group">
                                <label for="course_title">Título</label>
                                <input type="text" name="title" id="course_title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="course_description">Descripción</label>
                                <textarea name="description" id="course_description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="specialty_id">Especialidad</label>
                                <select name="specialty_id" id="specialty_id" class="form-control @error('specialty_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar especialidad</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty->specialty_id }}" {{ old('specialty_id') == $specialty->specialty_id ? 'selected' : '' }}>
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
                                <input type="number" name="hours_count" id="hours_count" class="form-control @error('hours_count') is-invalid @enderror" value="{{ old('hours_count') }}" min="1" step="1">
                                @error('hours_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="reference_price">Precio de referencia</label>
                                <input type="number" name="reference_price" id="reference_price" class="form-control @error('reference_price') is-invalid @enderror" value="{{ old('reference_price') }}" min="0" step="0.01" inputmode="decimal">
                                @error('reference_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="banner_path">Ruta del banner</label>
                                <input type="text" name="banner_path" id="banner_path" class="form-control @error('banner_path') is-invalid @enderror" value="{{ old('banner_path') }}">
                                @error('banner_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4 d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary">Guardar</button>
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
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" id="addModuleButton" data-toggle="modal" data-target="#moduleModal" disabled>
                                <i class="fas fa-plus"></i> Agregar Módulo
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <small id="moduleHelperText" class="text-muted d-block mb-3">Complete los detalles del curso para agregar módulos.</small>

                        <div id="temporaryModulesContainer" class="table-responsive" style="display: none;">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Orden</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="temporaryModulesTableBody"></tbody>
                            </table>
                        </div>

                        <div id="temporaryModulesEmptyState" class="alert alert-light border text-center mb-0">
                            Aún no has agregado módulos. Completa los detalles del curso y luego agrega el primero.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="moduleModal" tabindex="-1" role="dialog" aria-labelledby="moduleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="tempModuleForm">
                        @csrf
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Agregar módulo</button>
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
            const courseForm = document.getElementById('courseForm');
            const modulesDataInput = document.getElementById('modulesData');
            const addModuleButton = document.getElementById('addModuleButton');
            const moduleHelperText = document.getElementById('moduleHelperText');
            const temporaryModulesContainer = document.getElementById('temporaryModulesContainer');
            const temporaryModulesEmptyState = document.getElementById('temporaryModulesEmptyState');
            const temporaryModulesTableBody = document.getElementById('temporaryModulesTableBody');
            const tempModuleForm = document.getElementById('tempModuleForm');
            const courseTitleInput = document.getElementById('course_title');
            const specialtyInput = document.getElementById('specialty_id');
            const hoursInput = document.getElementById('hours_count');
            const priceInput = document.getElementById('reference_price');
            let temporaryModules = [];

            function validateModuleButton() {
                const canAdd = courseTitleInput.value.trim() !== ''
                    && specialtyInput.value !== ''
                    && hoursInput.value !== ''
                    && priceInput.value !== '';

                addModuleButton.disabled = !canAdd;
                moduleHelperText.textContent = canAdd
                    ? 'Puede agregar módulos para este curso.'
                    : 'Complete los detalles del curso para agregar módulos.';
            }

            [courseTitleInput, specialtyInput, hoursInput, priceInput].forEach(function (field) {
                field.addEventListener('input', validateModuleButton);
                field.addEventListener('change', validateModuleButton);
            });

            function renderTemporaryModules() {
                if (temporaryModules.length === 0) {
                    temporaryModulesContainer.style.display = 'none';
                    temporaryModulesEmptyState.style.display = 'block';
                    return;
                }

                temporaryModulesEmptyState.style.display = 'none';
                temporaryModulesContainer.style.display = 'block';
                temporaryModulesTableBody.innerHTML = temporaryModules.map(function (module, index) {
                    return `
                        <tr>
                            <td>${module.title}</td>
                            <td>${module.order}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-temp-module" data-index="${index}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                }).join('');
            }

            tempModuleForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const title = document.getElementById('module_title').value.trim();
                const description = document.getElementById('module_description').value.trim();
                const order = document.getElementById('module_order').value;

                if (title === '') {
                    return;
                }

                temporaryModules.push({
                    title: title,
                    description: description,
                    order: order || 0
                });

                renderTemporaryModules();
                $('#moduleModal').modal('hide');
                tempModuleForm.reset();
                document.getElementById('module_order').value = '0';
            });

            temporaryModulesTableBody.addEventListener('click', function (event) {
                const button = event.target.closest('.remove-temp-module');
                if (!button) {
                    return;
                }

                const index = Number(button.getAttribute('data-index'));
                if (!Number.isNaN(index)) {
                    temporaryModules.splice(index, 1);
                    renderTemporaryModules();
                }
            });

            courseForm.addEventListener('submit', function () {
                modulesDataInput.value = JSON.stringify(temporaryModules);
            });

            validateModuleButton();
        });
    </script>
@endpush
