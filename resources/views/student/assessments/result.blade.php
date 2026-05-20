@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        @php
                            // Se calcula el total usando la relación limpia
                            $totalScore = $attempt->assessment->questions->sum('score') ?: 20; 
                            $passed = $attempt->score >= ($totalScore / 2);
                        @endphp

                        <div class="mb-3 text-{{ $passed ? 'success' : 'danger' }}">
                            <i class="bi bi-{{ $passed ? 'check-circle-fill' : 'x-circle-fill' }}" style="font-size: 4rem;"></i>
                        </div>

                        <h1 class="h3 mb-3">Resultado de la evaluación</h1>

                        <div class="mb-4">
                            <span class="badge fs-5 px-4 py-3 bg-{{ $passed ? 'success' : 'danger' }}">
                                {{ $passed ? 'Aprobado' : 'No aprobado' }}
                            </span>
                        </div>

                        <p class="mb-2 text-muted">Evaluación: <strong>{{ $attempt->assessment->title }}</strong></p>
                        <p class="mb-4 fs-4">
                            Puntaje obtenido: <strong class="text-{{ $passed ? 'success' : 'danger' }}">{{ $attempt->score }}</strong> <span class="text-muted fs-6">de {{ $totalScore }} pts</span>
                        </p>

                        <div class="d-grid gap-2">
                            <a href="{{ route('student.courses.show', $attempt->assessment->training_id) }}" class="btn btn-primary btn-lg">
                                Volver al curso
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection