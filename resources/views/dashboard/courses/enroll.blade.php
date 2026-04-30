@extends('layouts.app')

@section('title', 'Inscribir estudiante')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Inscribir estudiante</h2>
            <p class="text-muted mb-0">Curso: <strong>{{ $course->titulo }}</strong></p>
        </div>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Volver a cursos</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Agregar estudiante</h5>
                    @if($capacitaciones->isEmpty())
                    <div class="alert alert-warning">
                        No hay capacitaciones creadas para este curso. Crea primero una capacitación para poder
                        inscribir estudiantes.
                    </div>
                    @else
                    <form action="{{ route('courses.enrollments.store', $course->idcurso) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="idcapacitacion" class="form-label">Capacitación</label>
                            <select name="idcapacitacion" id="idcapacitacion" class="form-select" required>
                                <option value="">Selecciona una capacitación</option>
                                @foreach($capacitaciones as $capacitacion)
                                <option value="{{ $capacitacion->idcapacitacion }}">
                                    #{{ $capacitacion->idcapacitacion }} · {{ $capacitacion->modalidad }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="idalumno" class="form-label">Estudiante</label>
                            <select name="idalumno" id="idalumno" class="form-select" required>
                                <option value="">Selecciona un estudiante</option>
                                @foreach($students as $student)
                                <option value="{{ $student->idusuario }}">
                                    {{ $student->nombres }} {{ $student->apellidos }} ({{ $student->username }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Inscribir estudiante</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Estudiantes inscritos</h5>
                    @if($enrollments->isEmpty())
                    <p class="text-muted">Aún no hay estudiantes inscritos en este curso.</p>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Modalidad</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->nombres }} {{ $enrollment->apellidos }}</td>
                                    <td>{{ $enrollment->modalidad }}</td>
                                    <td>{{ $enrollment->fechamatricula }}</td>
                                    <td>{{ $enrollment->estado }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection