@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px; font-family: system-ui, -apple-system, sans-serif;">
    
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 25px; gap: 15px;">
        <div>
            <span style="font-size: 12px; font-weight: bold; color: #2563eb; text-transform: uppercase; letter-spacing: 0.05em;">Revisión de Tareas</span>
            <h1 style="font-size: 24px; font-weight: bold; color: #1f2937; margin: 5px 0 0 0;">{{ $task->title }}</h1>
            <p style="font-size: 14px; color: #6b7280; margin: 5px 0 0 0;">
                Curso: <span style="font-weight: 500; color: #374151;">{{ $task->training->name ?? 'Curso' }}</span> 
                | Fecha límite: <span style="font-weight: 500; color: #ef4444;">{{ $task->due_date->format('d/m/Y H:i') }}</span>
            </p>
        </div>
        
        <a href="{{ route('teacher.courses.show', ['id' => $task->training_id, 'tab' => 'contenido']) }}" 
           style="display: inline-flex; align-items: center; padding: 8px 16px; bg-color: #f3f4f6; background: #e5e7eb; color: #374151; text-decoration: none; font-size: 14px; font-weight: 500; border-radius: 8px; transition: background 0.2s;">
            <svg style="width: 16px; height: 16px; margin-right: 8px; display: inline-block; vertical-align: middle;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver al Curso
        </a>
    </div>

    @if(session('success'))
        <div style="margin-bottom: 20px; padding: 15px; background-color: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; border-radius: 0 8px 8px 0; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 15px 20px; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
            <h2 style="font-size: 16px; font-weight: 600; color: #4b5563; margin: 0;">Alumnos y Entregas registradas</h2>
        </div>

        <div style="overflow-x: auto; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 600px;">
                <thead>
                    <tr style="background: #f3f4f6; border-bottom: 1px solid #e5e7eb; color: #4b5563; font-size: 12px; text-transform: uppercase;">
                        <th style="padding: 12px 20px;">Estudiante</th>
                        <th style="padding: 12px 20px;">Fecha de Envío</th>
                        <th style="padding: 12px 20px;">Contenido</th>
                        <th style="padding: 12px 20px; text-align: center;">Nota</th>
                        <th style="padding: 12px 20px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px; color: #374151;">
                    @forelse($submissions as $submission)
                        <tr style="border-bottom: 1px solid #edf2f7;">
                            <td style="padding: 15px 20px; font-weight: 500; color: #111827;">
                                {{ $submission->student->name ?? 'Estudiante' }}
                            </td>
                            <td style="padding: 15px 20px; color: #6b7280;">
                                {{ $submission->created_at ? $submission->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td style="padding: 15px 20px;">
                                <div style="max-w: 250px;">
                                    @if($submission->file_path)
                                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" 
                                           style="color: #2563eb; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center;">
                                            <svg style="width: 14px; height: 14px; margin-right: 4px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Ver Archivo
                                        </a>
                                    @endif
                                    @if($submission->comments)
                                        <p style="font-size: 12px; color: #6b7280; font-style: italic; margin: 4px 0 0 0;">
                                            "{{ $submission->comments }}"
                                        </p>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                @if(is_null($submission->grade))
                                    <span style="background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500;">Pendiente</span>
                                @else
                                    <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                                        {{ number_format($submission->grade, 1) }}
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 15px 20px; text-align: right;">
                                <button type="button" 
                                        onclick="openGradeModal({{ json_encode($submission) }})"
                                        style="background: #2563eb; color: white; border: none; padding: 6px 12px; font-size: 13px; font-weight: 500; border-radius: 6px; cursor: pointer;">
                                    {{ is_null($submission->grade) ? 'Evaluar' : 'Editar' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 40px; text-align: center; color: #9ca3af; font-style: italic;">
                                Ningún alumno ha realizado entregas para esta tarea todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="gradeModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; padding: 15px;">
    <div style="background: white; border-radius: 12px; max-width: 400px; w-width: 100%; width: 100%; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); border: 1px solid #f3f4f6;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; border-bottom: 1px solid #e5e7eb;">
            <h3 style="font-size: 18px; font-weight: bold; color: #1f2937; margin: 0;">Evaluar Entrega</h3>
            <button type="button" onclick="closeGradeModal()" style="background: none; border: none; font-size: 20px; color: #9ca3af; cursor: pointer;">&times;</button>
        </div>

        <form id="gradeForm" method="POST" action="">
            @csrf
            <div style="padding: 20px;">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #4b5563; margin-bottom: 5px;">Calificación (0 - 20)</label>
                    <input type="number" name="grade" id="modal_grade" step="0.1" min="0" max="20" required
                           style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; font-size: 14px; font-medium; color: #4b5563; margin-bottom: 5px;">Feedback / Retroalimentación</label>
                    <textarea name="feedback" id="modal_feedback" rows="4"
                              style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; resize: none;"></textarea>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; padding: 15px 20px; background: #f9fafb; border-top: 1px solid #e5e7eb; border-radius: 0 0 12px 12px;">
                <button type="button" onclick="closeGradeModal()"
                        style="background: white; border: 1px solid #d1d5db; color: #374151; padding: 8px 16px; font-size: 14px; font-weight: 500; border-radius: 6px; cursor: pointer;">
                    Cancelar
                </button>
                <button type="submit"
                        style="background: #2563eb; color: white; border: none; padding: 8px 16px; font-size: 14px; font-weight: 500; border-radius: 6px; cursor: pointer;">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('gradeModal');
    const form = document.getElementById('gradeForm');
    const gradeInput = document.getElementById('modal_grade');
    const feedbackTextarea = document.getElementById('modal_feedback');

    function openGradeModal(submission) {
        form.action = `/teacher/submissions/${submission.submission_id}/grade`;
        gradeInput.value = submission.grade !== null ? submission.grade : '';
        feedbackTextarea.value = submission.feedback !== null ? submission.feedback : '';
        
        // Corrección de despliegue universal en JS
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeGradeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        if (event.target === modal) {
            closeGradeModal();
        }
    }
</script>
@endsection