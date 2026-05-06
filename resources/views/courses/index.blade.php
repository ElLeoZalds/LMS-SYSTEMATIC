@extends('layouts.app')

@section('content')

    <div class="container">
        <h2>Cursos</h2>

        <ul>
            @foreach($courses as $course)
                <li>
                    {{ $course->title }} - S/. {{ $course->reference_price }}

                    @php
                        $training = optional($course->trainings->first());
                    @endphp

                    @if($training)
                        <form method="POST" action="{{ route('enroll.store', $training->training_id) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                Inscribirse
                            </button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

@endsection