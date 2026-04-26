@extends('layouts.app')

@section('title', 'Panel Administrador')

@section('content')
<div class="container-fluid mt-4">
    <div class="p-4 bg-white rounded-4 shadow-sm">
        <div class="row mb-4">
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card p-3 text-center card-hover h-100">
                    <h6>Cursos totales</h6>
                    <h3>5</h3>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card p-3 text-center card-hover h-100">
                    <h6>Estudiantes</h6>
                    <h3>120</h3>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card p-3 text-center card-hover h-100">
                    <h6>Tareas pendientes</h6>
                    <h3>8</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center card-hover h-100">
                    <h6>Progreso promedio</h6>
                    <h3>74%</h3>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
            <h5 class="mb-0">Mis cursos</h5>
            <a href="#" class="text-decoration-none">Ver todo</a>
        </div>

        <div class="row gy-4">
            <div class="col-md-4">
                <div class="card card-hover h-100">
                    <img src="https://picsum.photos/400/200" class="card-img-top" alt="Curso React Avanzado">
                    <div class="card-body">
                        <h6>Curso avanzado de React</h6>
                        <p class="text-muted small">35 estudiantes</p>
                        <div class="progress mb-2">
                            <div class="progress-bar" style="width: 60%"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-sm btn-outline-primary">Editar</button>
                            <button class="btn btn-sm btn-outline-success">Ver</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h5>Actividad reciente</h5>
            <ul class="list-group">
                <li class="list-group-item">Juan entregó una tarea en "Curso avanzado de React"</li>
                <li class="list-group-item">Nuevo estudiante inscrito en "Python para Data Science"</li>
            </ul>
        </div>
    </div>
</div>
@endsection