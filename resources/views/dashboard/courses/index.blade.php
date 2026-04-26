@extends('layouts.app')

@section('title', 'Course List')

@section('content')
    <div class="container mt-4">
        <h2>Course List</h2>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('courses.create') }}" class="btn btn-success mb-3">Create Course</a>
        <a href="{{ route('courses.report') }}" class="btn btn-danger mb-3">
            <i class="fa fa-file-pdf"></i> Generate PDF Report
        </a></thead>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Hours</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>{{ $course->idcurso }}</td>
                        <td>{{ $course->titulo }}</td>
                        <td>{{ $course->descripcion }}</td>
                        <td>{{ $course->cantidadhoras }}</td>
                        <td>S/ {{ $course->precioreferencial }}</td>
                        <td>
                            <a href="{{ route('courses.edit', $course->idcurso) }}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="{{ route('courses.enrollments', $course->idcurso) }}" class="btn btn-success btn-sm">Add student</a>
                            <form action="{{ route('courses.destroy', $course->idcurso) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection