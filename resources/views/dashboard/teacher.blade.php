@extends('layouts.app')

@section('title', 'Dashboard Docente')

@section('noSidebar')
@endsection

@section('content')

<div class="sidebar d-none d-lg-flex flex-column p-3" style="width: 260px;">
    <ul class="nav flex-column">
        <li><a href="{{ route('dashboard.user') }}" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-user fa-lg"></i> <span>User</span></a></li>
        <li><a href="{{ route('dashboard.calendar') }}" class="nav-link active d-flex align-items-center gap-3"><i class="fa fa-home fa-lg"></i> <span>Dashboard</span></a></li>
        <li><a href="{{ route('dashboard.my-courses') }}" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-book fa-lg"></i> <span>My Courses</span></a></li>
        <li><a href="{{ route('dashboard.learning-paths') }}" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-road fa-lg"></i> <span>Learning Paths</span></a></li>
        <li><a href="{{ route('dashboard.calendar') }}" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-calendar fa-lg"></i> <span>Calendar</span></a></li>
        <li><a href="{{ route('dashboard.certificates') }}" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-trophy fa-lg"></i> <span>Certificates</span></a></li>
        <li><a href="{{ route('dashboard.progress') }}" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-chart-bar fa-lg"></i> <span>Progress</span></a></li>
    </ul>

    <hr class="my-4">

    <h6 class="px-3 text-uppercase text-muted small fw-bold mb-3">Explorar</h6>
    <ul class="nav flex-column">
        <li><a href="{{ route('explore-courses.dashboard') }}" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-compass fa-lg"></i> <span>Explore Courses</span></a></li>
        <li><a href="#" class="nav-link d-flex align-items-center gap-3"><i class="fa fa-users fa-lg"></i> <span>Instructores</span></a></li>
    </ul>

    <div class="mt-auto px-3 pt-4 small text-muted">
        <p class="mb-1">© 2026 Systematic</p>
        <a href="#" class="text-decoration-none text-muted">Ayuda</a> •
        <a href="#" class="text-decoration-none text-muted">Privacidad</a>
    </div>
</div>

<!-- CONTENIDO -->
<div class="p-4 w-100">

    <!-- RESUMEN -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card p-3 text-center card-hover">
                <h6>Total Courses</h6>
                <h3>5</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center card-hover">
                <h6>Students</h6>
                <h3>10</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center card-hover">
                <h6>Pending tasks</h6>
                <h3>8</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 text-center card-hover">
                <h6>Average progress</h6>
                <h3>74%</h3>
            </div>
        </div>
    </div>

    <!-- MY COURSES -->
    <div class="d-flex justify-content-between mb-3">
        <h5>My Courses</h5>
        <a href="#">View all</a>
    </div>

    <div class="row">

        <!-- Course -->
        <div class="col-md-4">
            <div class="card card-hover">
                <img src="" class="card-img-top">
                <div class="card-body">
                    <h6>Advanced React Course</h6>
                    <p class="text-muted small">35 students</p>

                    <div class="progress mb-2">
                        <div class="progress-bar" style="width: 60%"></div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                        <button class="btn btn-sm btn-outline-success">View</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course -->
        <div class="col-md-4">
            <div class="card card-hover">
                <img src="" class="card-img-top">
                <div class="card-body">
                    <h6>Python for Data Science</h6>
                    <p class="text-muted small">50 students</p>

                    <div class="progress mb-2">
                        <div class="progress-bar" style="width: 80%"></div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button class="btn btn-sm btn-outline-primary">Edit</button>
                        <button class="btn btn-sm btn-outline-success">View</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- RECENT ACTIVITY -->
    <div class="mt-5">
        <h5>Recent Activity</h5>

        <ul class="list-group">
            <li class="list-group-item">
                Juan submitted an assignment for "Advanced React"
            </li>
            <li class="list-group-item">
                New student enrolled in "Python"
            </li>
        </ul>
    </div>

</div>

@endsection