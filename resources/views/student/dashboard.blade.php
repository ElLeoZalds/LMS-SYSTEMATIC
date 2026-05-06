@extends('layouts.app')

@section('content')

    <div class="container">

        <h2>🎓 Mi Dashboard</h2>

        <!-- STATS -->
        <div class="grid-stats">

            <div class="card stat">
                <h3>{{ $totalCourses }}</h3>
                <p>Cursos inscritos</p>
            </div>

            <div class="card stat">
                <h3>{{ $inProgress }}</h3>
                <p>En progreso</p>
            </div>

            <div class="card stat">
                <h3>{{ $completed }}</h3>
                <p>Completados</p>
            </div>

        </div>

        <!-- COURSES -->
        <h3>📚 Mis cursos</h3>

        <div class="grid">

            @foreach($enrollments as $enrollment)

                <div class="card">

                    <h4>{{ $enrollment->training->course->title }}</h4>

                    <p>👨‍🏫 {{ $enrollment->training->teacher->name ?? 'Sin profesor' }}</p>

                    <p>
                        Estado:
                        <strong>{{ $enrollment->status == 'A' ? 'Activo' : $enrollment->status }}</strong>
                    </p>

                    @php
                        $progress = $enrollment->status == 'A' ? 50 : 10;
                    @endphp

                    <div class="progress-bar">
                        <div class="progress" style="width: {{ $progress }}%"></div>
                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endsection