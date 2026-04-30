<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curso de Python</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container-fluid px-4">
            <img src="{{ asset('images/Systematic_logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 100px;">

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-primary">Continuar Curso</button>
                <img src="{{ asset('images/undraw_profile_2.svg') }}" class="rounded-circle" width="40">
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="mb-5">
            <h2 class="section-title">Complete Python Bootcamp 2026</h2>
            <p class="text-muted">Aprende Python desde cero hasta avanzado 🚀</p>

            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-primary" style="width: 68%"></div>
            </div>
            <small class="text-muted">68% completado</small>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <h5 class="section-title mb-3">Contenido del curso</h5>
                <div class="list-group">
                    <a href="#" class="list-group-item lesson-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>1. Introducción a Python</strong><br>
                            <small class="text-muted">Variables y tipos de datos</small>
                        </div>
                        <i class="fa fa-play-circle text-primary"></i>
                    </a>

                    <a href="#" class="list-group-item lesson-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>2. Estructuras de Control</strong><br>
                            <small class="text-muted">If, loops y lógica</small>
                        </div>
                        <i class="fa fa-play-circle text-primary"></i>
                    </a>

                    <a href="#" class="list-group-item lesson-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>3. Funciones</strong><br>
                            <small class="text-muted">Funciones básicas y avanzadas</small>
                        </div>
                        <i class="fa fa-play-circle text-primary"></i>
                    </a>

                </div>
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card border-0 rounded-4">
                    <div class="card-body">
                        <h6 class="fw-bold">Detalles del curso</h6>
                        <p class="text-muted small mb-2">Instructor: John Doe</p>
                        <p class="text-muted small mb-2">Duración: 40 horas</p>
                        <p class="text-muted small">Nivel: Principiante a avanzado</p>

                        <button class="btn btn-success w-100 mt-3">
                            <i class="fa fa-play me-2"></i> Continuar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>