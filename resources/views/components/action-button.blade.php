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

    $formId = $id ?? 'toggle-form-' . md5($route ?? uniqid('', true));
    $confirmTitle = $isActive ? 'Confirmar desactivación' : 'Confirmar activación';
    $confirmText = $isActive ? '¿Deseas desactivar este registro?' : '¿Deseas activar este registro?';
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
    <form id="{{ $formId }}" action="{{ $route ?? '#' }}" method="POST" class="d-inline">
        @csrf
        @method('PATCH')
    </form>
    <button type="button" class="{{ $buttonClass }}" onclick="Swal.fire({ icon: 'warning', title: '{{ $confirmTitle }}', text: '{{ $confirmText }}', showCancelButton: true, confirmButtonText: 'Sí, continuar', cancelButtonText: 'Cancelar', customClass: { popup: 'swal2-modal swal2-show', icon: 'swal2-icon-warning' } }).then(function(result){ if (result.isConfirmed) { document.getElementById('{{ $formId }}').submit(); } });">
        <i class="fas fa-{{ $iconName }}"></i> {{ $labelText }}
    </button>
@endif
