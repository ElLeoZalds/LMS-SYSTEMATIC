<nav class="navbar navbar-expand-lg bg-white border-bottom fixed-top">
    <div class="container-fluid px-3">

        <a href="/" class="navbar-brand">
            Systematic
        </a>

        @auth
            @php
                $role = optional(auth()->user()->roles->first())->name;
            @endphp

            <div class="ms-auto dropdown">
                <button class="btn btn-light d-flex align-items-center gap-2" data-bs-toggle="dropdown">

                    <i class="fa fa-user-circle fa-lg"></i>

                    <span class="d-none d-md-inline">
                        {{ auth()->user()->username }}
                    </span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    @if($role === 'Teacher')
                        <li><a class="dropdown-item" href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                    @elseif($role === 'Administrator')
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    @else
                        <li><a class="dropdown-item" href="{{ route('student.dashboard') }}">Dashboard</a></li>
                    @endif

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>

                </ul>
            </div>
        @endauth

    </div>
</nav>