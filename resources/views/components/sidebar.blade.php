@php
    $role = optional(auth()->user()->roles->first())->name;
    $person = optional(auth()->user()->person);
    $fullName = trim(($person->last_names ? $person->last_names . ' ' : '') . ($person->first_names ?? ''));
    $displayRole = 'Usuario';
    $sidebarTheme = [
        'Administrator' => ['bg-gradient-1', 'sidebar-dark', 'text-light', '#14132e00'],
        'Teacher' => ['bg-gradient-2', 'sidebar-dark', 'text-light', '#0d4aa600'],
        'Student' => ['bg-gradient-3', 'sidebar-dark', 'text-light', '#1b183800'],
    ][$role] ?? ['bg-gradient-3', 'sidebar-dark', 'text-light', '#1b183800'];

    [$sidebarBackgroundClass, $sidebarTextClass, $sidebarInfoTextClass, $sidebarAccentColor] = $sidebarTheme;

    if ($role === 'Administrator') {
        $displayRole = 'Administrador';
    } elseif ($role === 'Teacher') {
        $displayRole = 'Profesor';
    } elseif ($role === 'Student') {
        $displayRole = 'Estudiante';
    } elseif ($role) {
        $displayRole = $role;
    }
@endphp

<ul class="navbar-nav {{ $sidebarBackgroundClass }} sidebar {{ $sidebarTextClass }} accordion" id="accordionSidebar">

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

    <div class="text-center {{ $sidebarInfoTextClass }} small mb-3">
        <div>{{ $fullName ?: 'Usuario' }}</div>
        <div class="small">{{ $displayRole }}</div>
    </div>

    <hr class="sidebar-divider my-0">

    @if($role === 'Administrator')
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Inicio</span>
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

        <li class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.students.index') }}">
                <i class="fas fa-fw fa-user-graduate"></i>
                <span>Estudiantes</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.specialties.*', 'admin.courses.*', 'admin.trainings.*') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCatalogo" aria-expanded="false" aria-controls="collapseCatalogo">
                <i class="fas fa-fw fa-book"></i>
                <span>Catálogo Académico</span>
            </a>
            <div id="collapseCatalogo" class="collapse {{ request()->routeIs('admin.specialties.*', 'admin.courses.*', 'admin.trainings.*') ? 'show' : '' }}" aria-labelledby="headingCatalogo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Gestión académica:</h6>
                    <a class="collapse-item {{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}" href="{{ route('admin.specialties.index') }}">Especialidades</a>
                    <a class="collapse-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">Cursos</a>
                    <a class="collapse-item {{ request()->routeIs('admin.trainings.*') ? 'active' : '' }}" href="{{ route('admin.trainings.index') }}">Capacitaciones</a>
                </div>
            </div>
        </li>

    @elseif($role === 'Teacher')
        <li class="nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('teacher.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Inicio</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            Gestión
        </div>

        <li class="nav-item {{ request()->routeIs('teacher.courses', 'teacher.courses.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('teacher.courses') }}">
                <i class="fas fa-fw fa-book-open"></i>
                <span>Mis Cursos</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('teacher.calendar') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('teacher.calendar') }}">
                <i class="fas fa-fw fa-calendar-alt"></i>
                <span>Calendario</span>
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
                <span>Inicio</span>
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

        <li class="nav-item {{ request()->routeIs('student.calendar') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('student.calendar') }}">
                <i class="fas fa-fw fa-calendar-alt"></i>
                <span>Calendario</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>