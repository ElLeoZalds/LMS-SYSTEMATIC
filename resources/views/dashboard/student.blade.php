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

<div class="row g-4">
    <!-- Curso 1 -->
    <div class="col-lg-4 col-md-6">
        <a href="/curso/python-bootcamp" class="text-decoration-none text-dark">
            <div class="course-card card h-100 rounded-4 overflow-hidden">
                <img src="https://picsum.photos/id/1015/600/320" class="card-img-top">
                <div class="card-body">
                    <h6 class="fw-bold">Complete Python Bootcamp 2026</h6>
                    <p class="text-muted small">Por John Doe • 68%</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Curso 2 -->
    <div class="col-lg-4 col-md-6">
        <a href="/curso/javascript-course" class="text-decoration-none text-dark">
            <div class="course-card card h-100 rounded-4 overflow-hidden">
                <img src="https://picsum.photos/id/201/600/320" class="card-img-top">
                <div class="card-body">
                    <h6 class="fw-bold">The Complete JavaScript Course</h6>
                    <p class="text-muted small">Por Jassica William • 42%</p>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection