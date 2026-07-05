@props(['type' => 'edit', 'route' => null, 'id' => null, 'icon' => null, 'label' => null, 'class' => null, 'isActive' => null])

@php
    $iconName = $icon ?? match ($type) {
        'edit' => 'edit',
        'delete' => 'trash',
        'toggle' => ($isActive ? 'toggle-off' : 'toggle-on'),
        default => 'circle',
    };

    $labelText = $label ?? match ($type) {
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'toggle' => ($isActive ? 'Desactivar' : 'Activar'),
        default => 'Acción',
    };

    $buttonClass = $class ?? match ($type) {
        'edit' => 'btn btn-sm btn-warning',
        'delete' => 'btn btn-sm btn-outline-danger',
        'toggle' => ($isActive ? 'btn btn-sm btn-outline-danger' : 'btn btn-sm btn-outline-success'),
        default => 'btn btn-sm btn-secondary',
    };
@endphp

@if ($type === 'edit')
    <a href="{{ $route ?? '#' }}" class="{{ $buttonClass }}">
        <i class="fas fa-{{ $iconName }}"></i> {{ $labelText }}
    </a>
@elseif ($type === 'delete')
    <button type="button" class="{{ $buttonClass }}" onclick="confirmDelete('{{ $route ?? '#' }}')">
        <i class="fas fa-{{ $iconName }}"></i> {{ $labelText }}
    </button>
@elseif ($type === 'toggle')
    <form action="{{ $route ?? '#' }}" method="POST" class="d-inline">
        @csrf
        @method('PATCH')
        <button type="submit" class="{{ $buttonClass }}">
            <i class="fas fa-{{ $iconName }}"></i> {{ $labelText }}
        </button>
    </form>
@endif
