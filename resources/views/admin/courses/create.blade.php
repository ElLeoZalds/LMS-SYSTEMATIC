@extends('layouts.app')

@section('content')

    <div class="container-fluid px-4 py-4">

        <div class="card shadow mb-4">
            <div class="card-body p-4">
                <h1 class="h3 mb-4 text-gray-800">Crear Curso</h1>

                <form method="POST" action="{{ route('admin.courses.store') }}">
                    @csrf

                    <input type="text" name="title" placeholder="Título" class="form-control mb-2">

                    <textarea name="description" placeholder="Descripción" class="form-control mb-2"></textarea>

                    <input type="number" name="hours_count" placeholder="Horas" class="form-control mb-2">

                    <input type="number" name="reference_price" placeholder="Precio" class="form-control mb-2">

                    <button class="btn btn-primary">
                        Guardar
                    </button>

                </form>
            </div>
        </div>

    </div>

@endsection