@extends('layouts.app')

@section('title', 'Dashboard Estudiante')

@section('content')

<!-- Bienvenida -->
<div class="mb-5">
    <h2 class="section-header">¡Hola de nuevo, Joginder!</h2>
    <p class="text-muted">Continúa donde lo dejaste. Tienes 3 cursos en progreso.</p>
</div>

<!-- Continuar Aprendiendo -->
<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="section-header">Continuar Aprendiendo</h4>
    <a href="#" class="text-primary fw-semibold text-decoration-none">
        Ver todos <i class="fa fa-chevron-right ms-1"></i>
    </a>
</div>



@endsection