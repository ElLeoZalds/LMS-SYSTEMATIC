<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Systematic - Panel Docente</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background: #fff;
            border-right: 1px solid #ddd;
            position: sticky;
            top: 0;
        }

        .nav-link {
            color: #555;
            padding: 12px;
            border-radius: 10px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #e7f1ff;
            color: #0d6efd;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar bg-white shadow-sm px-4">
        <h5 class="fw-bold">Panel Docente</h5>

        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-primary">
                <i class="fa fa-plus"></i> Nuevo Curso
            </button>
            <i class="fa fa-bell fa-lg"></i>
            <img src="https://i.pravatar.cc/40" class="rounded-circle">
        </div>
    </nav>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div class="sidebar p-3" style="width:250px;">
            <ul class="nav flex-column">
                <li><a href="#" class="nav-link active"><i class="fa fa-home"></i> Dashboard</a></li>
                <li><a href="#" class="nav-link"><i class="fa fa-book"></i> Mis Cursos</a></li>
                <li><a href="#" class="nav-link"><i class="fa fa-users"></i> Estudiantes</a></li>
                <li><a href="#" class="nav-link"><i class="fa fa-tasks"></i> Tareas</a></li>
                <li><a href="#" class="nav-link"><i class="fa fa-chart-bar"></i> Estadísticas</a></li>
            </ul>
        </div>

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

    </div>

</body>

</html>