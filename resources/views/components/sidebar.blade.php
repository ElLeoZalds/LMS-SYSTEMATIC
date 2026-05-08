@php
    $role = optional(auth()->user()->roles->first())->name;
@endphp

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="
        @if($role === 'Administrator')
            {{ route('admin.dashboard') }}
        @elseif($role === 'Teacher')
            {{ route('teacher.dashboard') }}
        @else
            {{ route('student.dashboard') }}
        @endif
    ">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Systematic LMS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @if($role === 'Administrator')
        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Administración
        </div>

        <!-- Nav Item - Usuarios -->
        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Usuarios</span>
            </a>
        </li>

        <!-- Nav Item - Especialidades -->
        <li class="nav-item {{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.specialties.index') }}">
                <i class="fas fa-fw fa-tags"></i>
                <span>Especialidades</span>
            </a>
        </li>

        <!-- Nav Item - Cursos -->
        <li class="nav-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.courses.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Cursos</span>
            </a>
        </li>

        <!-- Nav Item - Capacitaciones -->
        <li class="nav-item {{ request()->routeIs('admin.trainings.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.trainings.index') }}">
                <i class="fas fa-fw fa-graduation-cap"></i>
                <span>Capacitaciones</span>
            </a>
        </li>

    @elseif($role === 'Teacher')
        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('teacher.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Gestión
        </div>

        <!-- Nav Item - Mis Cursos -->
        <li class="nav-item {{ request()->routeIs('teacher.courses.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('teacher.courses') }}">
                <i class="fas fa-fw fa-book-open"></i>
                <span>Mis Cursos</span>
            </a>
        </li>

        <!-- Nav Item - Evaluaciones -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-clipboard-check"></i>
                <span>Evaluaciones</span>
            </a>
        </li>

        @if(request()->routeIs('teacher.attendance.*') || request()->routeIs('teacher.students'))
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Capacitación Actual
            </div>

            @php $currentTrainingId = request()->route('id'); @endphp

            <!-- Nav Item - Asistencia -->
            <li class="nav-item {{ request()->routeIs('teacher.attendance.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('teacher.attendance', $currentTrainingId) }}">
                    <i class="fas fa-fw fa-clipboard-check"></i>
                    <span>Asistencia</span>
                </a>
            </li>

            <!-- Nav Item - Estudiantes -->
            <li class="nav-item {{ request()->routeIs('teacher.students') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('teacher.students', $currentTrainingId) }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Estudiantes</span>
                </a>
            </li>

            <!-- Nav Item - Crear Tarea -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('teacher.tasks.create', $currentTrainingId) }}">
                    <i class="fas fa-fw fa-plus"></i>
                    <span>Crear Tarea</span>
                </a>
            </li>
        @endif

    @elseif($role === 'Student')
        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('student.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Aprendizaje
        </div>

        <!-- Nav Item - Mis Cursos -->
        <li class="nav-item {{ request()->routeIs('student.courses.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('student.courses.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Mis Cursos</span>
            </a>
        </li>

        <!-- Nav Item - Calificaciones -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-trophy"></i>
                <span>Calificaciones</span>
            </a>
        </li>

        <!-- Nav Item - Certificados -->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fas fa-fw fa-certificate"></i>
                <span>Certificados</span>
            </a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->