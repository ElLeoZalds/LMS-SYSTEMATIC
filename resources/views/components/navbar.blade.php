<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm fixed-top">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-dark me-3 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-label="Abrir menú">
                <i class="fa fa-bars fa-2x"></i>
            </button>
            <a href="#" class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('images/Systematic_logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 100px;">
            </a>
        </div>
        <form class="d-flex flex-grow-1 mx-4" style="max-width: 520px;">
            <div class="input-group">
                <span class="input-group-text bg-light border-0">
                    <i class="fa fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-0 bg-light py-3" placeholder="Buscar cursos, lecciones o instructores...">
            </div>
        </form>
        <div class="d-flex align-items-center gap-4">
            <a href="{{ url('/dashboard/teacher') }}" class="btn btn-primary">
                Ir a vista profesor
            </a>
            <a href="{{ url('/dashboard/admin') }}" class="btn btn-primary">
                Ir a vista Administración
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