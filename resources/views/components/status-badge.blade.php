@props(['isActive' => false])

@php
    $badgeClass = $isActive ? 'bg-success' : 'bg-danger';
    $label = $isActive ? 'Activo' : 'Inactivo';
@endphp

<span class="badge {{ $badgeClass }}">{{ $label }}</span>
