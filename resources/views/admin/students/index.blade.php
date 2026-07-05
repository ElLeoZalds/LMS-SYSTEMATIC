@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Estudiantes</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.students.index') }}" class="row g-2 mb-3">
                    <div class="col-md-5">
                        <input type="text" name="search_name" value="{{ $searchName }}" class="form-control" placeholder="Buscar por nombre">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="search_email" value="{{ $searchEmail }}" class="form-control" placeholder="Buscar por correo">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">Filtrar</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Nombre</th>
                                <th class="align-middle">Correo</th>
                                <th class="align-middle">Estado</th>
                                <th class="align-middle">Fecha de registro</th>
                                <th class="align-middle text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                @php
                                    $fullName = trim(($student->person->first_names ?? 'Sin nombre') . ' ' . ($student->person->last_names ?? ''));
                                @endphp
                                <tr>
                                    <td class="align-middle">{{ $fullName }}</td>
                                    <td class="align-middle">{{ $student->person->email ?? 'Sin email' }}</td>
                                    <td class="align-middle">
                                        <span class="badge {{ $student->status === 'A' ? 'bg-success' : 'bg-danger' }}">{{ $student->status === 'A' ? 'Activo' : 'Inactivo' }}</span>
                                    </td>
                                    <td class="align-middle">{{ $student->created_at ? $student->created_at->format('d/m/Y H:i') : '—' }}</td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route('admin.students.show', $student->user_id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No hay estudiantes para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $students->appends(['search_name' => $searchName, 'search_email' => $searchEmail])->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
