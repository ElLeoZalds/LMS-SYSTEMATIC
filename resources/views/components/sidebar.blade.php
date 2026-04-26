<div class="sidebar-content d-flex flex-column h-100 p-3">
    <ul class="nav flex-column gap-1 mb-4">
        <li><a href="{{ route('dashboard.user') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-user fa-lg"></i> <span>User</span></a></li>
        <li><a href="{{ route('dashboard.calendar') }}" class="nav-link active d-flex align-items-center gap-3"><i
                    class="fa fa-home fa-lg"></i> <span>Dashboard</span></a></li>
        <li><a href="{{ route('dashboard.my-courses') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-book fa-lg"></i> <span>My Courses</span></a></li>
        <li><a href="{{ route('dashboard.learning-paths') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-road fa-lg"></i> <span>Learning Paths</span></a></li>
        <li><a href="{{ route('dashboard.calendar') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-calendar fa-lg"></i> <span>Calendar</span></a></li>
        <li><a href="{{ route('dashboard.certificates') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-trophy fa-lg"></i> <span>Certificates</span></a></li>
        <li><a href="{{ route('dashboard.progress') }}" class="nav-link d-flex align-items-center gap-3"><i
                    class="fa fa-chart-bar fa-lg"></i> <span>Progress</span></a></li>
    </ul>

    <div class="mt-auto pt-4 border-top">
        <h6 class="text-uppercase text-muted small fw-bold mb-3">Explore</h6>
        <ul class="nav flex-column gap-1 mb-3">
            <!-- <li><a href="{{ route('explore-courses.dashboard') }}" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-compass fa-lg"></i> <span>Explore Courses</span></a></li> -->
            <li><a href="#" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-users fa-lg"></i>
                    <span>Instructors</span></a></li>
        </ul>
    </div>

    <div class="mt-auto px-3 pt-4 small text-muted">
        <p class="mb-1">© 2026 Systematic</p>
        <a href="#" class="text-decoration-none text-muted">Help</a> •
        <a href="#" class="text-decoration-none text-muted">Privacy</a>
    </div>
</div>