@extends('layouts.app')

@section('content')

    <h2>Mis Aulas (Docente)</h2>

    <div class="row mt-3">

        @foreach($trainings as $training)

            <div class="col-md-4 mb-3">

                <div class="card p-3 shadow-sm">

                    <h5>{{ $training->course->title }}</h5>

                    <p>👨‍🎓 Estudiantes: {{ $training->enrollments->count() }}</p>

                    <p>💰 S/ {{ $training->price }}</p>

                    <span class="badge bg-info">
                        {{ $training->modality }}
                    </span>

                    <div class="mt-3">

                        <a href="{{ route('teacher.students', $training->training_id) }}" class="btn btn-primary btn-sm w-100">
                            Ver aula
                        </a>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

@endsection