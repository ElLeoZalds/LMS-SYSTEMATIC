    <div class="sidebar-content d-flex flex-column h-100 p-3">
        @php
        $role = session('user_role', null);
        if (empty($role)) {
        if (request()->routeIs('dashboard.admin') || request()->routeIs('modulos.*') || request()->routeIs('especialidades.*') || request()->routeIs('usuarios.*') || request()->routeIs('courses.*') || request()->routeIs('reportes.*')) {
        $role = 'admin';
        } elseif (request()->routeIs('dashboard.teacher') || request()->routeIs('explore-courses.dashboard') || request()->routeIs('courses.enrollments*')) {
        $role = 'teacher';
        } else {
        $role = 'student';
        }
        }
        @endphp

        <div class="mb-3">
            <span class="badge bg-secondary text-uppercase">Rol: {{ ucfirst($role) }}</span>
        </div>

        <ul class="nav flex-column gap-1 mb-4">
            <li><a href="{{ route('dashboard.user') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard.user') ? 'active' : '' }}"><i class="fa fa-user fa-lg"></i> <span>Perfil</span></a></li>
            <li><a href="{{ route('dashboard.calendar') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard.calendar') ? 'active' : '' }}"><i class="fa fa-home fa-lg"></i> <span>Calendario</span></a></li>
            <li><a href="{{ route('dashboard.my-courses') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard.my-courses') ? 'active' : '' }}"><i class="fa fa-book fa-lg"></i> <span>Mis cursos</span></a></li>
            <li><a href="{{ route('dashboard.progress') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard.progress') ? 'active' : '' }}"><i class="fa fa-chart-bar fa-lg"></i> <span>Progreso</span></a></li>
            <li><a href="{{ route('dashboard.certificates') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard.certificates') ? 'active' : '' }}"><i class="fa fa-trophy fa-lg"></i> <span>Certificados</span></a></li>
            <li><a href="{{ route('dashboard.learning-paths') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard.learning-paths') ? 'active' : '' }}"><i class="fa fa-road fa-lg"></i> <span>Rutas de Aprendizaje</span></a></li>
        </ul>

        @if($role === 'teacher')
        <div class="mb-4">
            <h6 class="text-uppercase text-muted small fw-bold mb-3">Docente</h6>
            <ul class="nav flex-column gap-1 mb-3">
                <li><a href="{{ route('courses.index') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('courses.index') ? 'active' : '' }}"><i class="fa fa-chalkboard-teacher fa-lg"></i> <span>Gestión de cursos</span></a></li>
            </ul>
        </div>
        @endif

        @if($role === 'admin')
        <div class="mb-4">
            <h6 class="text-uppercase text-muted small fw-bold mb-3">Administración</h6>
            <ul class="nav flex-column gap-1 mb-3">
                <li><a href="{{ route('dashboard.admin') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}"><i class="fa fa-tachometer-alt fa-lg"></i> <span>Panel admin</span></a></li>
                <li><a href="{{ route('courses.index') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('courses.index') ? 'active' : '' }}"><i class="fa fa-chalkboard-teacher fa-lg"></i> <span>Gestión de cursos</span></a></li>
                <li><a href="{{ route('modulos.specialtyActions') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('modulos.specialtyActions') || request()->routeIs('especialidades.*') ? 'active' : '' }}"><i class="fa fa-list fa-lg"></i> <span>Gestión de especialidades</span></a></li>
                <li><a href="{{ route('modulos.usuarios') }}" class="nav-link d-flex align-items-center gap-3 {{ request()->routeIs('modulos.usuarios') || request()->routeIs('usuarios.*') ? 'active' : '' }}"><i class="fa fa-users fa-lg"></i> <span>Gestión de usuarios</span></a></li>
            </ul>
        </div>
        @endif

        <div class="mt-auto px-3 pt-4 small text-muted">
            <p class="mb-1">© 2026 Systematic</p>
            <a href="#" class="text-decoration-none text-muted">Ayuda</a> •
            <a href="#" class="text-decoration-none text-muted">Privacidad</a>
        </div>
    </div>