<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm fixed-top">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-dark me-3 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarOffcanvas" aria-label="Open menu">
                <i class="fa fa-bars fa-2x"></i>
            </button>
            <a href="#" class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('images/Systematic_logo.png') }}" alt="Logo" class="img-fluid"
                    style="max-width: 100px;">
            </a>
        </div>
        <div class="d-flex flex-column flex-lg-row flex-grow-1 gap-3 gap-lg-4 align-items-center">
            <form class="flex-grow-1 w-100 w-lg-auto">
                <div class="input-group shadow-sm rounded overflow-hidden">
                    <span class="input-group-text bg-light border-0">
                        <i class="fa fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-light py-2"
                        placeholder="Search courses, lessons, or instructors...">
                </div>
            </form>

            <div class="d-flex flex-wrap align-items-center gap-2 gap-lg-3 justify-content-end w-100 w-lg-auto">
                <a href="{{ url('/dashboard/teacher') }}" class="btn btn-outline-primary btn-sm">Teacher</a>
                <a href="{{ url('/dashboard/admin') }}" class="btn btn-primary btn-sm">Admin</a>

                <div class="position-relative d-inline-flex align-items-center px-2 py-1 bg-light rounded-3">
                    <i class="fa fa-bell text-dark"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/undraw_profile_2.svg') }}" alt="User"
                        class="rounded-circle border border-2 border-white shadow-sm" width="40" height="40">
                    <div class="d-none d-md-block">
                        <small class="fw-bold text-dark">User</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>