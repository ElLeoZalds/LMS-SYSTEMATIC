<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Systematic - Dashboard</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous">

    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0b5ed7;
            --accent: #6f42c1;
            --success: #198754;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-brand span {
            background-color: var(--primary);
        }

        .sidebar {
            height: 100vh;
            overflow-y: auto;
            background: #fff;
            border-right: 1px solid #dee2e6;
            position: sticky;
            top: 0;
        }

        .nav-link {
            color: #555;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 6px;
            transition: all 0.2s;
        }

        .nav-link.active,
        .nav-link:hover {
            background-color: #e7f1ff;
            color: var(--primary);
            font-weight: 600;
        }

        .course-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(13, 110, 253, 0.15);
        }

        .progress {
            height: 6px;
        }

        .section-header {
            font-weight: 700;
            color: #2c3e50;
        }

        .live-dot {
            width: 8px;
            height: 8px;
            background-color: #198754;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
</head>

<body>

    <!-- TOP NAV -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm fixed-top">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-dark me-3 d-lg-none" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarOffcanvas" aria-label="Abrir menú">
                    <i class="fa fa-bars fa-2x"></i>
                </button>
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <img src="{{ asset('images/Systematic_logo.png') }}" alt="Logo" class="img-fluid"
                        style="max-width: 100px;">
                </a>
            </div>

            <!-- Buscador -->
            <form class="d-flex flex-grow-1 mx-4" style="max-width: 520px;">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-light py-3"
                        placeholder="Buscar cursos, lecciones o instructores...">
                </div>
            </form>

            <div class="d-flex align-items-center gap-4">
                <a href="{{ url('/dashboard/teacher') }}" class="btn btn-primary">
                    Ir a vista profesor
                </a>

                <div class="position-relative" style="cursor: pointer;">
                    <i class="fa fa-bell fa-2x text-dark"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/undraw_profile_2.svg') }}" alt="Usuario"
                        class="rounded-circle border border-2 border-white shadow-sm" width="40" height="40">
                    <div class="d-none d-md-block">
                        <small class="fw-bold text-dark">Usuario</small>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex" style="padding-top: 76px;">

        <!-- SIDEBAR IZQUIERDO -->
        <div class="sidebar d-none d-lg-flex flex-column p-3" style="width: 260px;">
            <ul class="nav flex-column">
                <li><a href="#" class="nav-link active d-flex align-items-center gap-3"><i class="fa fa-home fa-lg"></i>
                        <span>Dashboard</span></a></li>
                <li><a href="#" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-book fa-lg"></i>
                        <span>Mis Cursos</span></a></li>
                <li><a href="#" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-road fa-lg"></i>
                        <span>Learning Paths</span></a></li>
                <li><a href="#" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-calendar fa-lg"></i>
                        <span>Calendario</span></a></li>
                <li><a href="#" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-trophy fa-lg"></i>
                        <span>Certificados</span></a></li>
                <li><a href="#" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-chart-bar fa-lg"></i>
                        <span>Progreso</span></a></li>
            </ul>

            <hr class="my-4">

            <h6 class="px-3 text-uppercase text-muted small fw-bold mb-3">Explorar</h6>
            <ul class="nav flex-column">
                <li><a href="#" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-compass fa-lg"></i>
                        <span>Explorar Cursos</span></a></li>
                <li><a href="#" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-users fa-lg"></i>
                        <span>Instructores</span></a></li>
            </ul>

            <div class="mt-auto px-3 pt-4 small text-muted">
                <p class="mb-1">© 2026 Systematic</p>
                <a href="#" class="text-decoration-none text-muted">Ayuda</a> •
                <a href="#" class="text-decoration-none text-muted">Privacidad</a>
            </div>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-grow-1 p-4">

            <!-- Bienvenida -->
            <div class="mb-5">
                <h2 class="section-header">¡Hola de nuevo, Joginder!</h2>
                <p class="text-muted">Continúa donde lo dejaste. Tienes 3 cursos en progreso.</p>
            </div>

            <!-- Continuar Aprendiendo -->
            <div class="d-flex justify-content-between align-items-end mb-3">
                <h4 class="section-header">Continuar Aprendiendo</h4>
                <a href="#" class="text-primary fw-semibold text-decoration-none">Ver todos <i
                        class="fa fa-chevron-right ms-1"></i></a>
            </div>

            <div class="row g-4">
                <!-- Curso en progreso 1 -->
                <div class="col-lg-4 col-md-6">
                    <a href="/curso/python-bootcamp" class="text-decoration-none text-dark">
                        <div class="course-card card h-100 rounded-4 overflow-hidden">
                            <img src="https://picsum.photos/id/1015/600/320" class="card-img-top" alt="Python Bootcamp">
                            <div class="card-body">
                                <h6 class="fw-bold">Complete Python Bootcamp 2026</h6>
                                <p class="text-muted small">Por John Doe • 68% completado</p>
                                <div class="progress mb-2">
                                    <div class="progress-bar bg-primary" style="width: 68%"></div>
                                </div>
                                <small class="text-muted">Última lección: Funciones Avanzadas</small>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Curso en progreso 2 -->
                <div class="col-lg-4 col-md-6">
                    <a href="/curso/javascript-course" class="text-decoration-none text-dark">
                        <div class="course-card card h-100 rounded-4 overflow-hidden">
                            <img src="https://picsum.photos/id/201/600/320" class="card-img-top" alt="JavaScript">
                            <div class="card-body">
                                <h6 class="fw-bold">The Complete JavaScript Course</h6>
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
        </div>

        <!-- RIGHT SIDEBAR (solo en pantallas muy grandes) -->
        <div class="right-sidebar d-none d-xl-flex flex-column p-4 bg-white border-start"
            style="width: 300px; height: calc(100vh - 76px); overflow-y: auto;">
            <div class="text-center mb-4">
                <img src="{{ asset('images/undraw_profile_2.svg') }}" alt="Usuario" class="rounded-circle mb-3"
                    width="110" height="110">
                <h5 class="fw-bold">Usuario</h5>
                <p class="text-muted">Estudiante • 7 cursos activos</p>
            </div>

            <h6 class="section-header mb-3">Próximas actividades</h6>
            <div class="list-group list-group-flush">
                <div class="list-group-item border-0 px-0">
                    <small class="text-muted">Hoy • 15:00</small><br>
                    <strong>Lección: Hooks en React</strong>
                </div>
                <div class="list-group-item border-0 px-0">
                    <small class="text-muted">Mañana</small><br>
                    <strong>Entrega: Proyecto Python</strong>
                </div>
            </div>

            <hr class="my-4">

            <div class="alert alert-light border-0 rounded-3">
                <strong>¡Felicidades!</strong><br>
                <small>Has completado el 85% de tu Learning Path "Full Stack Developer".</small>
            </div>
        </div>
    </div>

    <!-- Offcanvas para móvil y tablet -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="offcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasLabel">Systematic</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body p-0">
            <!-- Aquí puedes copiar el mismo contenido del sidebar izquierdo si quieres -->
            <div class="p-3">
                <ul class="nav flex-column">
                    <li><a href="#" class="nav-link active"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li><a href="#" class="nav-link"><i class="fa fa-book"></i> Mis Cursos</a></li>
                    <!-- ... resto de enlaces ... -->
                </ul>
            </div>
        </div>
    </div>

    <footer class="bg-white border-top mt-5">
        <div class="container-fluid px-4 py-4">
            <div class="row align-items-center">

                <!-- Logo / Marca -->
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="fw-bold mb-1">Systematic</h6>
                    <small class="text-muted">Aprende sin límites 🚀</small>
                </div>

                <!-- Links -->
                <div class="col-md-4 mb-3 mb-md-0 text-md-center">
                    <a href="#" class="text-decoration-none text-muted me-3">Ayuda</a>
                    <a href="#" class="text-decoration-none text-muted me-3">Privacidad</a>
                    <a href="#" class="text-decoration-none text-muted">Términos</a>
                </div>

                <!-- Copyright -->
                <div class="col-md-4 text-md-end">
                    <small class="text-muted">
                        © 2026 Systematic. Todos los derechos reservados.
                    </small>
                </div>

            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>