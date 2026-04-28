@extends('layouts.app')

@section('title', 'Explorar cursos')

@section('content')

<div class="row g-4">
    @foreach($courses as $course)
    <div class="col-lg-4 col-md-6">
        <a href="/curso/{{ $course->idcurso }}" class="text-decoration-none text-dark">
            <div class="course-card card h-100 rounded-4 overflow-hidden">

                <img src="{{ $course->pathbanner ?? '' }}" class="card-img-top">

                <div class="card-body">
                    <h6 class="fw-bold">{{ $course->titulo }}</h6>

                    <p class="text-muted small">
                        {{ $course->descripcion }}
                    </p>

                    <small class="text-muted">
                        {{ $course->cantidadhoras }} horas • S/ {{ $course->precioreferencial }}
                    </small>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

@endsection