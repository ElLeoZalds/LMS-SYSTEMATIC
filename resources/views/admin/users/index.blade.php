@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Usuarios</h1>
            <button class="btn btn-primary disabled" aria-disabled="true">+ Crear usuario</button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Usuarios</h5>
                    <button class="btn btn-outline-primary btn-sm" type="button" data-toggle="collapse" data-target="#filtersCollapse" aria-expanded="false">
                        <i class="fas fa-filter"></i> Filtros
                    </button>
                </div>
                <div class="collapse mt-3" id="filtersCollapse">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small">Buscar</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Nombre o DNI" value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Rol</label>
                            <select name="role" class="form-select">
                                <option value="">Todos</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Año</label>
                            <select name="year" class="form-select">
                                <option value="">Todos</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary me-2">Aplicar</button>
                            @if(request()->hasAny(['search', 'role', 'year']))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger">Limpiar</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                @php
                    $usersByRole = $users->groupBy(function($user) {
                        return $user->roles->pluck('name')->first() ?? 'Sin Rol';
                    });
                @endphp
                <div class="accordion" id="usersAccordion">
                    @foreach($usersByRole as $roleName => $roleUsers)
                        <div class="accordion-item border-0 shadow-sm mb-2 rounded">
                            <h2 class="accordion-header" id="heading{{ str_replace(' ', '', $roleName) }}">
                                <button class="accordion-button collapsed bg-white text-dark fw-bold" type="button" data-toggle="collapse" data-target="#collapse{{ str_replace(' ', '', $roleName) }}" aria-expanded="false" aria-controls="collapse{{ str_replace(' ', '', $roleName) }}">
                                    <i class="fas fa-users me-2"></i> {{ $roleName }} ({{ $roleUsers->count() }} usuarios)
                                </button>
                            </h2>
                            <div id="collapse{{ str_replace(' ', '', $roleName) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ str_replace(' ', '', $roleName) }}" data-parent="#usersAccordion">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="align-middle"></th>
                                                    <th class="align-middle">Nombre</th>
                                                    <th class="align-middle">Detalles</th>
                                                    <th class="align-middle">Estado</th>
                                                    <th class="align-middle text-end">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($roleUsers as $user)
                                                    @php
                                                        $fullName = trim(($user->person->first_names ?? 'Sin nombre') . ' ' . ($user->person->last_names ?? ''));
                                                        $initials = strtoupper(substr($user->person->first_names ?? 'S', 0, 1) . substr($user->person->last_names ?? 'N', 0, 1));
                                                    @endphp
                                                    <tr>
                                                        <td class="align-middle pe-3">
                                                            <div class="avatar-circle rounded-circle bg-avatar-{{ ($loop->index % 4) + 1 }}">
                                                                {{ $initials }}
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="fw-bold">{{ $fullName }}</div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="text-muted small">{{ $user->person->email ?? 'Sin email' }}</div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="badge bg-primary">{{ $roleName }}</span>
                                                        </td>
                                                        <td class="align-middle text-end">
                                                            <button class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection