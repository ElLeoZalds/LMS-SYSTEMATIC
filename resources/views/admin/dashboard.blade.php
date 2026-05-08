@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    <div class="container-fluid px-4 py-4">

        <h1 class="h3 mb-4 text-gray-800">Dashboard Administrador</h1>

        <div class="row g-3">

            <div class="col-md-3">
                <div class="card p-3 shadow-sm text-center">
                    <h5>Estudiantes</h5>
                    <h2>{{ $totalStudents }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow-sm text-center">
                    <h5>Cursos</h5>
                    <h2>{{ $totalCourses }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow-sm text-center">
                    <h5>Trainings</h5>
                    <h2>{{ $totalTrainings }}</h2>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow-sm text-center">
                    <h5>Matriculas</h5>
                    <h2>{{ $totalEnrollments }}</h2>
                </div>
            </div>

        </div>

        <hr class="my-4">

        <h5>Últimos usuarios</h5>

        <table class="table table-striped mt-3">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>
                @foreach($latestUsers as $user)
                    <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->status }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
@endsection