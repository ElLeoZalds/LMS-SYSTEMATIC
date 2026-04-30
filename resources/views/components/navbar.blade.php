<nav class="navbar navbar-expand-lg bg-white border-bottom fixed-top">
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
        <div class="d-flex flex-column flex-lg-row flex-grow-1 gap-3 gap-lg-4 align-items-center">
            <form class="flex-grow-1 w-100 w-lg-auto">
                <div class="input-group rounded overflow-hidden">
                    <span class="input-group-text bg-light border-0">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-light py-2"
                        placeholder="Buscar cursos, lecciones o instructores...">
                </div>
            </form>

            <div class="d-flex flex-wrap align-items-center gap-2 gap-lg-3 justify-content-end w-100 w-lg-auto">
                @php
                $loggedUser = session('username', 'Usuario');
                $loggedRole = ucfirst(session('user_role', 'estudiante'));
                @endphp
                <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-pill dropdown-toggle d-flex align-items-center gap-2"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('images/undraw_profile_2.svg') }}" alt="Usuario"
                            class="rounded-circle border border-2 border-white" width="40" height="40">
                        <div class="d-none d-md-flex flex-column text-start">
                            <strong class="text-dark">{{ $loggedUser }}</strong>
                            <small class="text-muted text-nowrap">{{ $loggedRole }}</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end mt-2">
                        <li><a class="dropdown-item" href="{{ route('dashboard.user') }}">Mi perfil</a></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard.main') }}">Panel principal</a></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard.my-courses') }}">Mis cursos</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>