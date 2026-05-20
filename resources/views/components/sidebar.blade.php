@php
    $role = optional(auth()->user()->roles->first())->name;
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

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

    <hr class="sidebar-divider my-0">

    @if($role === 'Administrator')
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Administración
        </div>

        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Usuarios</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.specialties.index') }}">
                <i class="fas fa-fw fa-tags"></i>
                <span>Especialidades</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.courses.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Cursos</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.trainings.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.trainings.index') }}">
                <i class="fas fa-fw fa-graduation-cap"></i>
                <span>Capacitaciones</span>
            </a>
        </li>

    @elseif($role === 'Teacher')
        <li class="nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('teacher.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Gestión
        </div>

        <li class="nav-item {{ request()->routeIs('teacher.courses.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('teacher.courses') }}">
                <i class="fas fa-fw fa-book-open"></i>
                <span>Mis Cursos</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('teacher.assessments.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('teacher.assessments.index') }}">
                <i class="fas fa-fw fa-clipboard-check"></i>
                <span>Evaluaciones</span>
            </a>
        </li>

        @if(request()->routeIs('teacher.attendance.*') || request()->routeIs('teacher.students'))
            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Capacitación Actual
            </div>

            @php 
                $currentTrainingId = request()->route('id') 
                    ?? ($training->training_id ?? (optional(Auth::user()->trainings->first())->training_id ?? 1)); 
            @endphp

            <li class="nav-item {{ request()->routeIs('teacher.attendance.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('teacher.attendance.create', ['training_id' => $currentTrainingId]) }}">
                    <i class="fas fa-fw fa-calendar-check"></i>
                    <span>Asistencia</span>
                </a>
            </li>

            <li class="nav-item {{ request()->routeIs('teacher.students') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('teacher.students', ['id' => $currentTrainingId]) }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Estudiantes</span>
                </a>
            </li>
        @endif

    @elseif($role === 'Student')
        <li class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('student.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Aprendizaje
        </div>

        <li class="nav-item {{ request()->routeIs('student.courses.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('student.courses.index') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>Mis Cursos</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>