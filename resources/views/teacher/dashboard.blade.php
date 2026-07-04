@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="mb-4">
            <h1 class="h3 mb-4 text-gray-800">Resumen de Actividad</h1>
            <p class="text-muted small">Vista general de tus capacitaciones y tareas recientes.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 mt-4">
            <h2 class="h5 font-weight-bold mb-4">Actividad Reciente</h2>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small fw-bold">Tarea</th>
                            <th class="small fw-bold">Curso</th>
                            <th class="small fw-bold">Fecha de Creación</th>
                            <th class="small fw-bold">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $activity)
                            <tr>
                                <td class="small">{{ $activity->title }}</td>
                                <td class="small">{{ optional($activity->training->course)->title ?? 'N/A' }}{{ optional($activity->training->start_date)->format(' (Y-m)') }}</td>
                                <td class="small">{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge {{ $activity->active ? 'bg-success' : 'bg-secondary' }} small">
                                        {{ $activity->active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted small py-3">No hay actividades recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createAssessmentModal" tabindex="-1" aria-labelledby="createAssessmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="createAssessmentModalLabel">Filtrar Alumnos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="selectTrainingStudents" class="form-label small">Selecciona una capacitación</label>
                        <select id="selectTrainingStudents" class="form-select">
                            <option value="">-- Seleccionar --</option>
                            @foreach($trainings as $t)
                                <option value="{{ $t->training_id }}">{{ $t->course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-muted small mb-0">Selecciona una capacitación y luego haz clic en "Ver estudiantes" para filtrar.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnViewStudents" class="btn btn-primary" disabled>Ver estudiantes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="filterModalLabel">Filtrar Promedios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="selectTrainingAvg" class="form-label small">Selecciona una capacitación</label>
                        <select id="selectTrainingAvg" class="form-select">
                            <option value="">-- Seleccionar --</option>
                            @foreach($trainings as $t)
                                <option value="{{ $t->training_id }}">{{ $t->course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-muted small mb-0">Selecciona una capacitación y luego haz clic en "Ver promedios".</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnViewAverages" class="btn btn-primary" disabled>Ver promedios</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectStudents = document.getElementById('selectTrainingStudents');
            const btnViewStudents = document.getElementById('btnViewStudents');

            const selectAvg = document.getElementById('selectTrainingAvg');
            const btnViewAverages = document.getElementById('btnViewAverages');

            if (selectStudents) {
                selectStudents.addEventListener('change', function () {
                    btnViewStudents.disabled = !this.value;
                });

                btnViewStudents.addEventListener('click', async function () {
                    const id = selectStudents.value;
                    if (!id) return;
                    btnViewStudents.disabled = true;
                    btnViewStudents.textContent = 'Cargando...';

                    try {
                        const res = await fetch("{{ url('teacher/ajax/students') }}".replace(/\/$/, '') + '/' + id);
                        const json = await res.json();
                        const container = selectStudents.closest('.modal-body');
                        renderStudentsTable(container, json.data || []);
                    } catch (err) {
                        console.error(err);
                        alert('Error al cargar estudiantes.');
                    } finally {
                        btnViewStudents.disabled = false;
                        btnViewStudents.textContent = 'Ver estudiantes';
                    }
                });
            }

            if (selectAvg) {
                selectAvg.addEventListener('change', function () {
                    btnViewAverages.disabled = !this.value;
                });

                btnViewAverages.addEventListener('click', async function () {
                    const id = selectAvg.value;
                    if (!id) return;
                    btnViewAverages.disabled = true;
                    btnViewAverages.textContent = 'Cargando...';

                    try {
                        const res = await fetch("{{ url('teacher/ajax/averages') }}".replace(/\/$/, '') + '/' + id);
                        const json = await res.json();
                        const container = selectAvg.closest('.modal-body');
                        renderAveragesTable(container, json.data || []);
                    } catch (err) {
                        console.error(err);
                        alert('Error al cargar promedios.');
                    } finally {
                        btnViewAverages.disabled = false;
                        btnViewAverages.textContent = 'Ver promedios';
                    }
                });
            }
        });

        function renderStudentsTable(container, students) {
            let html = '';
            if (!students || students.length === 0) {
                html = '<p class="text-muted small">No se encontraron estudiantes para esta capacitación.</p>';
            } else {
                html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Nombre</th><th>Email</th></tr></thead><tbody>';
                students.forEach(s => {
                    html += `<tr><td>${escapeHtml(s.name)}</td><td>${escapeHtml(s.email || '')}</td></tr>`;
                });
                html += '</tbody></table></div>';
            }

            // Reemplaza contenido del modal preservando el área del select
            const existing = container.querySelector('.filter-results');
            if (existing) existing.remove();
            const div = document.createElement('div');
            div.className = 'filter-results mt-3';
            div.innerHTML = html;
            container.appendChild(div);
        }

        function renderAveragesTable(container, items) {
            let html = '';
            if (!items || items.length === 0) {
                html = '<p class="text-muted small">No se encontraron evaluaciones para esta capacitación.</p>';
            } else {
                html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Evaluación</th><th>Promedio</th><th>Intentos</th><th>Activo</th></tr></thead><tbody>';
                items.forEach(it => {
                    html += `<tr><td>${escapeHtml(it.title)}</td><td>${it.average !== null ? it.average : 'N/A'}</td><td>${it.attempts}</td><td>${it.active ? 'Sí' : 'No'}</td></tr>`;
                });
                html += '</tbody></table></div>';
            }

            const existing = container.querySelector('.filter-results');
            if (existing) existing.remove();
            const div = document.createElement('div');
            div.className = 'filter-results mt-3';
            div.innerHTML = html;
            container.appendChild(div);
        }

        function escapeHtml(unsafe) {
            return String(unsafe)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    </script>
@endsection