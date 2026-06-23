@extends('layouts.app')

@section('content')
<style>
    /* ─── Page Shell ─── */
    .cert-preview-shell {
        background: linear-gradient(135deg, #0f172a 0%, #1a2744 60%, #0f2027 100%);
        min-height: calc(100vh - 60px);
        padding: 40px 20px 60px;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    /* ─── Header ─── */
    .cert-header {
        text-align: center;
        margin-bottom: 36px;
    }
    .cert-header .badge-approved {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        padding: 6px 18px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 14px;
        box-shadow: 0 4px 15px rgba(16,185,129,0.4);
        animation: glow-pulse 2.5s ease-in-out infinite;
    }
    @keyframes glow-pulse {
        0%, 100% { box-shadow: 0 4px 15px rgba(16,185,129,0.4); }
        50%  { box-shadow: 0 6px 30px rgba(16,185,129,0.7); }
    }
    .cert-header h1 {
        color: #f8fafc;
        font-size: 2rem;
        font-weight: 800;
        margin: 0 0 6px;
    }
    .cert-header p {
        color: #94a3b8;
        font-size: 15px;
        margin: 0;
    }

    /* ─── Flip Card ─── */
    .flip-wrapper {
        perspective: 1600px;
        max-width: 860px;
        margin: 0 auto 30px;
        cursor: pointer;
    }
    .flip-card {
        position: relative;
        width: 100%;
        padding-top: 70.74%;
        transform-style: preserve-3d;
        transition: transform 0.85s cubic-bezier(.45,.05,.55,.95);
        border-radius: 18px;
    }
    .flip-card.flipped {
        transform: rotateY(180deg);
    }
    .flip-front,
    .flip-back {
        position: absolute;
        inset: 0;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(0,0,0,0.6), 0 0 0 1px rgba(255,255,255,0.08);
    }
    .flip-back {
        transform: rotateY(180deg);
    }

    /* ─── Certificate Face ─── */
    .cert-face {
        position: absolute;
        inset: 0;
    }
    .cert-face img.bg-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .anverso-name {
        position: absolute;
        top: 41.5%;
        left: 0; right: 0;
        text-align: center;
        font-size: clamp(14px, 3.5vw, 30px);
        font-weight: 800;
        color: #0b1a30;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0 10%;
    }
    .anverso-course {
        position: absolute;
        top: 55%;
        left: 0; right: 0;
        text-align: center;
        font-size: clamp(10px, 2.2vw, 20px);
        font-weight: 700;
        color: #0b1a30;
        padding: 0 12%;
    }
    .anverso-date {
        position: absolute;
        top: 66%;
        left: 0; right: 0;
        text-align: center;
        font-size: clamp(8px, 1.5vw, 14px);
        color: #444;
    }
    .reverso-actividad {
        position: absolute;
        top: 18%;
        left: 24%;
        font-size: clamp(8px, 1.5vw, 13px);
        font-weight: 700;
        color: #111;
        max-width: 48%;
    }
    .reverso-modulo {
        position: absolute;
        top: 25%;
        left: 22%;
        font-size: clamp(8px, 1.5vw, 13px);
        font-weight: 700;
        color: #111;
        max-width: 48%;
    }
    .reverso-duracion {
        position: absolute;
        top: 31.5%;
        left: 24%;
        font-size: clamp(8px, 1.5vw, 13px);
        font-weight: 700;
        color: #111;
    }
    .reverso-fecha {
        position: absolute;
        top: 38%;
        left: 35%;
        font-size: clamp(8px, 1.5vw, 13px);
        font-weight: 700;
        color: #111;
    }
    .reverso-nota {
        position: absolute;
        top: 44.8%;
        left: 18%;
        font-size: clamp(10px, 1.8vw, 16px);
        font-weight: 800;
        color: #111;
    }
    .reverso-qr {
        position: absolute;
        top: 11%;
        right: 7%;
        width: 14%;
        height: auto;
    }
    .reverso-qr svg {
        width: 100%;
        height: auto;
    }

    /* ─── Flip hint ─── */
    .flip-hint {
        text-align: center;
        color: #64748b;
        font-size: 13px;
        margin-bottom: 22px;
        letter-spacing: 0.5px;
    }
    .flip-hint i { vertical-align: middle; margin-right: 4px; }

    /* ─── Side dots ─── */
    .side-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-bottom: 32px;
    }
    .dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: #334155;
        transition: background .3s, transform .3s;
        cursor: pointer;
    }
    .dot.active {
        background: #6366f1;
        transform: scale(1.3);
    }

    /* ─── Info Panel ─── */
    .info-panel {
        max-width: 860px;
        margin: 0 auto 32px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
    }
    .info-card {
        background: rgba(30,41,59,0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 14px;
        padding: 18px 20px;
        color: #f1f5f9;
    }
    .info-card .label {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 5px;
    }
    .info-card .value {
        font-size: 15px;
        font-weight: 700;
    }
    .info-card .value.grade {
        font-size: 22px;
        color: #10b981;
    }
    .code-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(99,102,241,0.15);
        border: 1px solid rgba(99,102,241,0.35);
        color: #a5b4fc;
        font-size: 13px;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 999px;
        letter-spacing: 1px;
    }

    /* ─── Action Buttons ─── */
    .action-row {
        max-width: 860px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        justify-content: center;
    }
    .btn-download {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        padding: 14px 36px;
        border-radius: 999px;
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 6px 25px rgba(16,185,129,0.45);
    }
    .btn-download:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 35px rgba(16,185,129,0.55);
        color: #fff;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.12);
        color: #cbd5e1;
        padding: 14px 28px;
        border-radius: 999px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s;
    }
    .btn-back:hover {
        background: rgba(255,255,255,0.12);
        color: #fff;
    }
</style>

<div class="cert-preview-shell">

    {{-- ── Header ── --}}
    <div class="cert-header">
        <div>
            <span class="badge-approved">
                <i class="bi bi-patch-check-fill"></i>
                CERTIFICADO APROBADO
            </span>
        </div>
        <h1>Vista Previa del Certificado</h1>
        <p>
            Haz clic en el certificado para ver el <strong style="color:#a5b4fc;">anverso</strong> y el
            <strong style="color:#a5b4fc;">reverso</strong>
        </p>
    </div>

    {{-- ── Flip Card ── --}}
    <div class="flip-wrapper" id="flipWrapper" title="Haz clic para girar">
        <div class="flip-card" id="flipCard">

            {{-- ── ANVERSO ── --}}
            <div class="flip-front">
                <div class="cert-face">
                    <img src="{{ asset('images/certificado-bg.jpg') }}" class="bg-img" alt="Anverso del certificado">
                    <div class="anverso-name">
                        {{ $enrollment->student->person->first_names ?? '' }}
                        {{ $enrollment->student->person->last_names ?? '' }}
                    </div>
                    <div class="anverso-course">{{ $enrollment->training->course->title ?? '' }}</div>
                    <div class="anverso-date">
                        Otorgado el {{ \Carbon\Carbon::parse($enrollment->enrollment_date)->translatedFormat('d \d\e F \d\e Y') }}
                    </div>
                </div>
            </div>

            {{-- ── REVERSO ── --}}
            <div class="flip-back">
                <div class="cert-face">
                    <img src="{{ asset('images/certificado-reverso-bg.jpg') }}" class="bg-img" alt="Reverso del certificado">
                    <div class="reverso-actividad">{{ $enrollment->training->course->title ?? '' }}</div>
                    <div class="reverso-modulo">{{ $enrollment->training->course->specialty->specialty ?? 'General' }}</div>
                    <div class="reverso-duracion">{{ $enrollment->training->course->hours_count ?? 0 }} horas académicas</div>
                    <div class="reverso-fecha">{{ \Carbon\Carbon::parse($enrollment->enrollment_date)->format('d/m/Y') }}</div>
                    <div class="reverso-nota">{{ $averageGrade }}</div>
                    <div class="reverso-qr">{!! $qrCode !!}</div>
                </div>
            </div>

        </div>
    </div>

    <p class="flip-hint"><i class="bi bi-hand-index-thumb"></i> Haz clic en el certificado para girarlo</p>

    <div class="side-dots">
        <div class="dot active" id="dot0" onclick="showSide(0)" title="Anverso"></div>
        <div class="dot"        id="dot1" onclick="showSide(1)" title="Reverso"></div>
    </div>

    {{-- ── Info Cards ── --}}
    <div class="info-panel">
        <div class="info-card">
            <div class="label">Estudiante</div>
            <div class="value">
                {{ $enrollment->student->person->first_names ?? '' }}
                {{ $enrollment->student->person->last_names ?? '' }}
            </div>
        </div>
        <div class="info-card">
            <div class="label">Curso</div>
            <div class="value">{{ $enrollment->training->course->title ?? '' }}</div>
        </div>
        <div class="info-card">
            <div class="label">Especialidad</div>
            <div class="value">{{ $enrollment->training->course->specialty->specialty ?? 'General' }}</div>
        </div>
        <div class="info-card">
            <div class="label">Duración</div>
            <div class="value">{{ $enrollment->training->course->hours_count ?? 0 }} horas</div>
        </div>
        <div class="info-card">
            <div class="label">Nota Final</div>
            <div class="value grade">{{ $averageGrade }}</div>
        </div>
        <div class="info-card">
            <div class="label">Código de Verificación</div>
            <div class="value">
                <span class="code-chip">
                    <i class="bi bi-qr-code"></i>
                    {{ $certificateCode }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── Actions ── --}}
    <div class="action-row">
        <a href="{{ route('student.courses.certificate', $trainingId) }}" class="btn-download">
            <i class="bi bi-file-earmark-arrow-down-fill"></i>
            Descargar Certificado PDF
        </a>
        <a href="{{ route('student.courses.show', [$trainingId]) }}?tab=calificaciones" class="btn-back">
            <i class="bi bi-arrow-left"></i>
            Volver al Curso
        </a>
    </div>

</div>

<script>
    var flipped = false;
    document.getElementById('flipWrapper').addEventListener('click', function () {
        flipped = !flipped;
        document.getElementById('flipCard').classList.toggle('flipped', flipped);
        updateDots();
    });
    function showSide(side) {
        flipped = (side === 1);
        document.getElementById('flipCard').classList.toggle('flipped', flipped);
        updateDots();
    }
    function updateDots() {
        document.getElementById('dot0').classList.toggle('active', !flipped);
        document.getElementById('dot1').classList.toggle('active', flipped);
    }
</script>
@endsection
