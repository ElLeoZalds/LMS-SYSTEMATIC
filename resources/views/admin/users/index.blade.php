@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4    -4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Usuarios</h1>
            <button class="btn btn-primary disabled" aria-disabled="true">+ Crear usuario</button>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle"></th>
                                <th class="align-middle">Nombre</th>
                                <th class="align-middle">Detalles</th>
                                <th class="align-middle">Estado</th>
                                <th class="align-middle text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                @php
                                    $fullName = trim(($user->person->first_names ?? 'Sin nombre') . ' ' . ($user->person->last_names ?? ''));
                                    $initials = strtoupper(substr($user->person->first_names ?? 'S', 0, 1) . substr($user->person->last_names ?? 'N', 0, 1));
                                    $roleName = $user->roles->pluck('name')->first() ?? 'Usuario';
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
                                        <div class="text-muted small">{{ $user->email ?? 'Sin email' }}</div>
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge bg-primary">{{ $roleName }}</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <button class="btn btn-sm btn-info">Ver Detalles</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{ $users->links() }}
    </div>
@endsection