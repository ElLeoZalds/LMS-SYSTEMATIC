<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <ul class="navbar-nav ml-auto">
        @php
            $currentUser = auth()->user();
            $activeRoleName = session('active_role_name') ?: optional($currentUser?->roles->first())->name;
            $role = $activeRoleName;
            $unreadNotifications = $role === 'Student' ? $currentUser?->unreadNotifications()->latest()->take(10)->get() ?? collect() : collect();
            $unreadNotificationsCount = $role === 'Student' ? $currentUser?->unreadNotifications->count() : 0;
        @endphp

        @if($role === 'Student')
            <li class="nav-item dropdown no-arrow mr-3 notification-nav-item">
                <a class="nav-link notification-bell {{ $unreadNotificationsCount > 0 ? 'has-new' : '' }}" href="#" id="notificationsDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    @if($unreadNotificationsCount > 0)
                        <span class="badge badge-danger badge-counter notification-badge">
                            {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                        </span>
                    @endif
                </a>

                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in notification-dropdown" aria-labelledby="notificationsDropdown">
                    <div class="dropdown-header d-flex justify-content-between align-items-center">
                        <span class="font-weight-bold text-gray-800">Notificaciones</span>
                        @if($unreadNotificationsCount > 0)
                            <button type="button" class="btn btn-link btn-sm p-0 text-primary mark-all-read-btn">Marcar todas como leídas</button>
                        @endif
                    </div>

                    @if($unreadNotificationsCount > 0)
                        <div class="notification-scroll">
                            @foreach($unreadNotifications as $notification)
                                @php
                                    $notificationTitle = data_get($notification->data, 'announcement_title', data_get($notification->data, 'title', data_get($notification->data, 'message', 'Nuevo anuncio')));
                                    $notificationCourse = data_get($notification->data, 'course_title', data_get($notification->data, 'course', 'Curso'));
                                    $notificationUrl = data_get($notification->data, 'url', route('student.dashboard'));
                                @endphp
                                <a class="dropdown-item notification-item-link" href="{{ $notificationUrl }}" data-notification-id="{{ $notification->id }}" data-url="{{ $notificationUrl }}">
                                    <div class="d-flex align-items-start">
                                        <div class="mr-2 mt-1"><i class="fas fa-bullhorn text-primary"></i></div>
                                        <div class="flex-grow-1">
                                            <div class="font-weight-bold text-gray-800 small">{{ \Illuminate\Support\Str::limit($notificationTitle, 55) }}</div>
                                            <div class="text-gray-600 small">{{ \Illuminate\Support\Str::limit($notificationCourse, 35) }}</div>
                                            <div class="text-gray-500 small">{{ $notification->created_at ? $notification->created_at->diffForHumans() : '' }}</div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center text-primary font-weight-bold" href="{{ route('student.dashboard') }}">Ver todas las notificaciones</a>
                    @else
                        <div class="dropdown-item text-center text-gray-600 py-3">
                            <i class="fas fa-bell-slash fa-2x mb-2 d-block text-gray-300"></i>
                            No tienes notificaciones nuevas
                        </div>
                    @endif
                </div>
            </li>
        @endif

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    {{ auth()->user()->username }}
                </span>
                @if($currentUser && $currentUser->hasMultipleRoles() && $activeRoleName)
                    <span class="badge badge-primary ml-2">{{ $activeRoleName }}</span>
                @endif
                <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                @if($currentUser && $currentUser->hasMultipleRoles())
                    <div class="dropdown-header font-weight-bold text-gray-800">
                        <i class="bi bi-arrow-repeat me-2"></i>Cambiar rol
                    </div>
                    @foreach($currentUser->roles as $roleOption)
                        <form method="POST" action="{{ route('role.set') }}" class="d-block">
                            @csrf
                            <input type="hidden" name="role" value="{{ $roleOption->role_id }}">
                            <button type="submit" class="dropdown-item {{ session('active_role_id') == $roleOption->role_id ? 'active font-weight-bold text-primary' : '' }}">
                                <i class="bi bi-{{ $roleOption->name === 'Administrator' ? 'shield-lock' : ($roleOption->name === 'Teacher' ? 'person-workspace' : 'book') }} me-2"></i>
                                {{ $roleOption->name }}
                                @if(session('active_role_id') == $roleOption->role_id)
                                    <span class="text-muted small">(actual)</span>
                                @endif
                            </button>
                        </form>
                    @endforeach
                    <div class="dropdown-divider"></div>
                @endif

                @if($role === 'Teacher')
                    <a class="dropdown-item" href="{{ route('teacher.dashboard') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Dashboard
                    </a>
                @elseif($role === 'Administrator')
                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Dashboard
                    </a>
                @else
                    <a class="dropdown-item" href="{{ route('student.dashboard') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Dashboard
                    </a>
                @endif

                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                    Mi perfil
                </a>

                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button class="dropdown-item" type="submit">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </li>

    </ul>

    <style>
        .notification-nav-item .notification-bell {
            position: relative;
            color: #4e73df;
            font-size: 1rem;
            padding: 0.45rem 0.6rem;
            border-radius: 999px;
            transition: all 0.2s ease-in-out;
        }

        .notification-nav-item .notification-bell:hover {
            background-color: #eef2ff;
            color: #224abe;
        }

        .notification-nav-item .notification-bell.has-new {
            animation: bellPulse 1.8s infinite;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            font-size: 0.65rem;
            padding: 0.2rem 0.35rem;
            border-radius: 999px;
            animation: badgePulse 1.8s infinite;
        }

        .notification-dropdown {
            width: 320px;
            max-height: 420px;
            overflow: hidden;
            border: 1px solid rgba(78, 115, 223, 0.12);
            border-radius: 0.75rem;
            padding-top: 0.35rem;
            padding-bottom: 0.35rem;
        }

        .notification-scroll {
            max-height: 310px;
            overflow-y: auto;
        }

        .notification-item-link {
            border-left: 3px solid transparent;
            transition: all 0.2s ease-in-out;
        }

        .notification-item-link:hover {
            background-color: #f8f9fc;
            border-left-color: #4e73df;
        }

        .mark-all-read-btn {
            text-decoration: none;
            font-size: 0.8rem;
        }

        @keyframes bellPulse {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-8deg); }
            75% { transform: rotate(8deg); }
        }

        @keyframes badgePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }

        @media (max-width: 576px) {
            .notification-dropdown {
                width: calc(100vw - 1rem);
                right: 0.5rem !important;
                left: 0.5rem !important;
                max-width: none;
            }

            .notification-badge {
                font-size: 0.6rem;
                padding: 0.18rem 0.3rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            const markAllReadButtons = document.querySelectorAll('.mark-all-read-btn');
            const notificationItems = document.querySelectorAll('.notification-item-link');

            markAllReadButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    fetch('{{ route('student.notifications.mark-all-read') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({})
                    })
                        .then(function (response) {
                            return response.json();
                        })
                        .then(function () {
                            const badge = document.querySelector('.notification-badge');
                            if (badge) {
                                badge.remove();
                            }

                            const bell = document.querySelector('.notification-bell');
                            if (bell) {
                                bell.classList.remove('has-new');
                            }

                            const scrollContainer = document.querySelector('.notification-scroll');
                            if (scrollContainer) {
                                scrollContainer.innerHTML = '<div class="dropdown-item text-center text-gray-600 py-3"><i class="fas fa-bell-slash fa-2x mb-2 d-block text-gray-300"></i>No tienes notificaciones nuevas</div>';
                            }

                            const markAllButton = document.querySelector('.mark-all-read-btn');
                            if (markAllButton) {
                                markAllButton.remove();
                            }

                            if (window.SwalToast) {
                                SwalToast.fire({ icon: 'success', title: 'Notificaciones marcadas como leídas' });
                            }
                        })
                        .catch(function () {
                            if (window.SwalToast) {
                                SwalToast.fire({ icon: 'error', title: 'No se pudieron actualizar las notificaciones' });
                            }
                        });
                });
            });

            notificationItems.forEach(function (item) {
                item.addEventListener('click', function (event) {
                    const notificationId = this.getAttribute('data-notification-id');
                    const redirectUrl = this.getAttribute('data-url') || '{{ route('student.dashboard') }}';

                    if (!notificationId) {
                        return true;
                    }

                    event.preventDefault();

                    fetch('{{ route('student.notifications.mark-read', ['notification_id' => ':id']) }}'.replace(':id', notificationId), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({})
                    }).finally(function () {
                        window.location.href = redirectUrl;
                    });
                });
            });
        });
    </script>

</nav>