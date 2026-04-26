<div class="sidebar-content d-flex flex-column h-100 p-3">
    <ul class="nav flex-column gap-1 mb-4">
        <li><a href="{{ route('dashboard.usuario') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-user fa-lg"></i> <span>Usuario</span></a></li>
        <li><a href="{{ route('dashboard.calendario') }}" class="nav-link active d-flex align-items-center gap-3"><i
                    class="fa fa-home fa-lg"></i> <span>Dashboard</span></a></li>
        <li><a href="{{ route('dashboard.mis-cursos') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-book fa-lg"></i> <span>Mis Cursos</span></a></li>
        <li><a href="{{ route('dashboard.learning-paths') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-road fa-lg"></i> <span>Learning Paths</span></a></li>
        <li><a href="{{ route('dashboard.calendario') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-calendar fa-lg"></i> <span>Calendario</span></a></li>
        <li><a href="{{ route('dashboard.certificados') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-trophy fa-lg"></i> <span>Certificados</span></a></li>
        <li><a href="{{ route('dashboard.progreso') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-chart-bar fa-lg"></i> <span>Progreso</span></a></li>
    </ul>

    <div class="mt-auto pt-4 border-top">
        <h6 class="text-uppercase text-muted small fw-bold mb-3">Explorar</h6>
        <ul class="nav flex-column gap-1 mb-3">
            <!-- <li><a href="{{ route('explore-courses.dashboard') }}" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-compass fa-lg"></i> <span>Explorar Cursos</span></a></li> -->
            <li><a href="#" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-users fa-lg"></i>
                    <span>Instructores</span></a></li>
        </ul>
    </div>

    <div class="mt-auto px-3 pt-4 small text-muted">
        <p class="mb-1">© 2026 Systematic</p>
        <a href="#" class="text-decoration-none text-muted">Ayuda</a> •
        <a href="#" class="text-decoration-none text-muted">Privacidad</a>
    </div>
</div>