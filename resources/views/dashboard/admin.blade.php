@extends('layouts.app')

@section('title', 'Dashboard Administrador')

@section('content')

        <!-- CONTENIDO -->
        <div class="p-4 w-100">

            <!-- RESUMEN -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card p-3 text-center card-hover">
                        <h6>Total Cursos</h6>
                        <h3>5</h3>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 text-center card-hover">
                        <h6>Estudiantes</h6>
                        <h3>120</h3>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 text-center card-hover">
                        <h6>Tareas Pendientes</h6>
                        <h3>8</h3>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-3 text-center card-hover">
                        <h6>Promedio Progreso</h6>
                        <h3>74%</h3>
                    </div>
                </div>
            </div>

            <!-- MIS CURSOS -->
            <div class="d-flex justify-content-between mb-3">
                <h5>Mis Cursos</h5>
                <a href="#">Ver todos</a>
            </div>

            <div class="row">

                <!-- Curso -->
                <div class="col-md-4">
                    <div class="card card-hover">
                        <img src="https://picsum.photos/400/200" class="card-img-top">
                        <div class="card-body">
                            <h6>Curso React Avanzado</h6>
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

                <!-- Curso -->
                <div class="col-md-4">
                    <div class="card card-hover">
                        <img src="https://picsum.photos/401/200" class="card-img-top">
                        <div class="card-body">
                            <h6>Python para Data Science</h6>
                            <p class="text-muted small">50 estudiantes</p>

                            <div class="progress mb-2">
                                <div class="progress-bar" style="width: 80%"></div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button class="btn btn-sm btn-outline-primary">Editar</button>
                                <button class="btn btn-sm btn-outline-success">Ver</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ACTIVIDAD RECIENTE -->
            <div class="mt-5">
                <h5>Actividad Reciente</h5>

                <ul class="list-group">
                    <li class="list-group-item">
                        Juan entregó tarea en "React Avanzado"
                    </li>
                    <li class="list-group-item">
                        Nuevo estudiante inscrito en "Python"
                    </li>
                </ul>
            </div>

        </div>

@endsection