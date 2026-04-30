@extends('layouts.app')

@section('title', 'Certificados')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-column flex-md-row">
        <div>
            <h2>Certificados</h2>
            <p class="text-muted">Ver y descargar tus certificados obtenidos aquí.</p>
        </div>
        <a href="#" class="btn btn-primary btn-sm">Solicitar certificado</a>
    </div>

    <div class="row g-4">
        @forelse($certificates as $certificate)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $certificate->titulo }}</h5>
                    <p class="text-muted small mb-2">{{ $certificate->modalidad }}</p>
                    <p class="mb-1"><strong>Fecha de matrícula:</strong> {{ $certificate->fechamatricula }}</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Descargar</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-light">No hay certificados disponibles por el momento.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection