@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-4">
        <a href="{{ route('teacher.courses') }}" class="text-decoration-none">
            <i class="bi bi-arrow-left me-2"></i>Volver a mis cursos
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Tomar Asistencia - {{ $training->course->title }}</h1>

        <form action="{{ route('teacher.attendance.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="training_id" value="{{ $training->training_id }}">

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Presente</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ausente</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $enrollment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $enrollment->student->person->first_name }} {{ $enrollment->student->person->last_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="radio" name="attendances[{{ $loop->index }}][status]" value="P"
                                       class="w-6 h-6 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
                                       checked>
                                <input type="hidden" name="attendances[{{ $loop->index }}][student_id]" value="{{ $enrollment->student_id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="radio" name="attendances[{{ $loop->index }}][status]" value="A"
                                       class="w-6 h-6 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Guardar Asistencia
                </button>
            </div>
        </form>
    </div>
</div>
@endsection