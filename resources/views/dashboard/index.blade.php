@extends('layouts.app')

@section('title', 'Panel')

@section('content')

<div class="mb-5">
    <h2 class="section-header">¡Hola de nuevo, Joginder!</h2>
    <p class="text-muted">Continúa donde lo dejaste. Tienes 3 cursos en progreso.</p>
</div>

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="section-header">Continuar Aprendiendo</h4>
</div>

<div class="row g-4">
    <div class="col-lg-4 col-md-6">
        <a href="/curso/python-bootcamp" class="text-decoration-none text-dark">
            <div class="course-card card h-100 rounded-4 overflow-hidden">
                <img src="https://picsum.photos/id/1015/600/320" class="card-img-top" alt="Bootcamp de Python">
                <div class="card-body">
                    <h6 class="fw-bold">Bootcamp completo de Python 2026</h6>
                    <p class="text-muted small">Por John Doe • 68% completado</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-primary" style="width: 68%"></div>
                    </div>
                    <small class="text-muted">Última lección: Funciones Avanzadas</small>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-4 col-md-6">
        <a href="/curso/javascript-course" class="text-decoration-none text-dark">
            <div class="course-card card h-100 rounded-4 overflow-hidden">
                <img src="https://picsum.photos/id/201/600/320" class="card-img-top" alt="Curso de JavaScript">
                <div class="card-body">
                    <h6 class="fw-bold">El curso completo de JavaScript</h6>
                    <p class="text-muted small">Por Jassica William • 42% completado</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-primary" style="width: 42%"></div>
                    </div>
                    <small class="text-muted">Última lección: Async/Await</small>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection