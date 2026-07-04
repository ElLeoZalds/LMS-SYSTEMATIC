@props(['title', 'subtitle' => null, 'actionRoute' => null, 'actionLabel' => null, 'actionIcon' => 'plus'])

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        @endif
    </div>

    @if ($actionRoute && $actionLabel)
        <a href="{{ $actionRoute }}" class="btn btn-primary">
            <i class="fas fa-{{ $actionIcon }}"></i> {{ $actionLabel }}
        </a>
    @endif
</div>
