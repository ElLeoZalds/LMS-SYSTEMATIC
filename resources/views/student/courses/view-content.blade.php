@extends('layouts.app')

@section('title', $content->title . ' | ' . ($training->course->title ?? 'Curso'))

@push('styles')
    <style>
        .content-shell {
            min-height: calc(100vh - 180px);
        }

        .content-player {
            background: #f8f9fc;
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid #e3e6f0;
        }

        .content-player iframe,
        .content-player video {
            width: 100%;
            min-height: 420px;
            height: 100%;
            border: 0;
        }

        .content-player .ratio-wrapper {
            aspect-ratio: 16 / 9;
            width: 100%;
        }

        .module-index {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: .25rem;
        }

        .module-index .list-group-item.active {
            background-color: #eef2ff;
            border-color: #c7d2fe;
            color: #312e81;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="h4 mb-1 text-gray-900">{{ $content->title }}</h2>
                <p class="text-muted mb-0">{{ $training->course->title ?? 'Curso' }} · {{ $content->module->title ?? 'Módulo' }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('student.courses.show', $training->training_id) }}?tab=inicio" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Volver al curso
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        @if(session('module_completed'))
            <div class="alert alert-success" role="alert">
                <strong>¡Módulo completado!</strong> Has finalizado todos los contenidos de este módulo.
                <div class="mt-2">
                    <a href="{{ route('student.courses.show', $training->training_id) }}?tab=inicio" class="btn btn-success btn-sm">
                        <i class="bi bi-house-door me-1"></i>Volver al Dashboard del Curso
                    </a>
                </div>
            </div>
        @endif

        <div class="row g-4 content-shell">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $content->title }}</h5>
                                <p class="text-muted small mb-0">{{ $content->description ?: 'Explora este contenido en modo inmersivo.' }}</p>
                            </div>
                            <span class="badge bg-primary text-uppercase">{{ $content->type }}</span>
                        </div>

                        <div class="content-player mb-4">
                            @if($content->type === 'pdf')
                                <div class="ratio-wrapper">
                                    <iframe src="{{ asset('storage/' . ($content->file_path ?? '')) }}" title="PDF del contenido"></iframe>
                                </div>
                            @elseif($content->type === 'video')
                                @php $videoUrl = $content->video_url ?: ($content->description ?? ''); @endphp
                                <div class="ratio-wrapper">
                                    @if($videoUrl && (str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be')))
                                        @php $embedUrl = str_replace('watch?v=', 'embed/', $videoUrl); @endphp
                                        <iframe src="{{ $embedUrl }}" allowfullscreen></iframe>
                                    @elseif($videoUrl && str_contains($videoUrl, 'vimeo.com'))
                                        @php $embedUrl = str_replace('vimeo.com/', 'player.vimeo.com/video/', $videoUrl); @endphp
                                        <iframe src="{{ $embedUrl }}" allowfullscreen></iframe>
                                    @elseif($videoUrl)
                                        <video controls preload="metadata" poster="{{ asset('images/default-video.jpg') }}">
                                            <source src="{{ $videoUrl }}" type="video/mp4">
                                        </video>
                                    @else
                                        <div class="p-4 text-muted">No hay una URL de video disponible para este contenido.</div>
                                    @endif
                                </div>
                            @elseif($content->type === 'link')
                                <div class="text-center py-5">
                                    <i class="bi bi-link-45deg display-4 text-primary mb-3"></i>
                                    <h5 class="fw-bold">Abrir recurso externo</h5>
                                    <p class="text-muted">Este contenido está vinculado a una URL externa.</p>
                                    <a href="{{ $content->description ?? '#' }}" target="_blank" class="btn btn-primary btn-lg">
                                        <i class="bi bi-box-arrow-up-right me-2"></i>Abrir enlace
                                    </a>
                                </div>
                            @else
                                <div class="p-4">
                                    <h6 class="fw-bold">Contenido de texto</h6>
                                    <div class="text-muted">{!! nl2br(e($content->description ?? 'Sin contenido disponible.')) !!}</div>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-top pt-3">
                            <div class="d-flex gap-2">
                                @if($previousContent)
                                    <a href="{{ route('student.courses.view-content', ['training' => $training->training_id, 'content' => $previousContent->content_id]) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-arrow-left me-1"></i>Anterior
                                    </a>
                                @endif
                                @if($nextContent)
                                    <a href="{{ route('student.courses.view-content', ['training' => $training->training_id, 'content' => $nextContent->content_id]) }}" class="btn btn-outline-secondary btn-sm">
                                        Siguiente<i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                @endif
                            </div>
                            <form action="{{ route('student.courses.content.complete', ['training' => $training->training_id, 'content' => $content->content_id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle-fill me-2"></i>✅ Marcar como completado y continuar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-bold">Índice del módulo</h6>
                        <small class="text-muted">{{ $content->module->title ?? 'Módulo' }}</small>
                    </div>
                    <div class="card-body">
                        <div class="module-index">
                            <div class="list-group list-group-flush">
                                @foreach($moduleContents as $moduleContent)
                                    @php $isCurrent = $moduleContent->content_id === $content->content_id; @endphp
                                    @php $completed = in_array($moduleContent->content_id, $completedContentIds, true); @endphp
                                    <a href="{{ route('student.courses.view-content', ['training' => $training->training_id, 'content' => $moduleContent->content_id]) }}"
                                       class="list-group-item list-group-item-action {{ $isCurrent ? 'active' : '' }} d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="me-2 {{ $completed ? 'bi bi-check-circle-fill text-success' : 'bi bi-circle text-secondary' }}"></i>
                                            {{ $moduleContent->title }}
                                        </span>
                                        <small class="text-uppercase {{ $isCurrent ? 'text-primary' : 'text-muted' }}">{{ $moduleContent->type }}</small>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
