@props(['isActive' => false])

@php
    $badgeClass = $isActive ? 'badge-success' : 'badge-danger';
    $label = $isActive ? 'Activo' : 'Inactivo';
@endphp

<span class="badge {{ $badgeClass }}">{{ $label }}</span>
