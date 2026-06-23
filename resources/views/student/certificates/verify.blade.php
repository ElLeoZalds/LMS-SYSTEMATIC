<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Certificado - LMS-SYSTEMATIC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
            padding: 20px;
        }
        .verify-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .success-icon {
            font-size: 64px;
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
            width: 100px;
            height: 100px;
            line-height: 100px;
            border-radius: 50%;
            margin: 0 auto 24px;
            border: 2px solid rgba(16, 185, 129, 0.2);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .code-badge {
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 24px;
            font-size: 14px;
            letter-spacing: 1px;
        }
        .detail-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            text-align: left;
        }
        .detail-label {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: #f1f5f9;
        }
        .brand-logo {
            font-size: 24px;
            font-weight: 700;
            color: #6366f1;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

    <div class="verify-card">
        <div class="brand-logo"><i class="bi bi-mortarboard-fill me-2"></i>LMS-SYSTEMATIC</div>
        <div class="subtitle">Sistema de Verificación de Certificados</div>
        
        <div class="success-icon">
            <i class="bi bi-shield-check"></i>
        </div>
        
        <h3 class="fw-bold mb-2">¡Certificado Válido!</h3>
        <p class="text-muted mb-4">Confirmamos que este certificado electrónico es auténtico y fue emitido por LMS-SYSTEMATIC.</p>
        
        <span class="code-badge">CÓDIGO: {{ $code }}</span>
        
        <div class="detail-item">
            <div class="detail-label">Estudiante</div>
            <div class="detail-value">{{ $enrollment->student->person->first_names }} {{ $enrollment->student->person->last_names }}</div>
        </div>
        
        <div class="detail-item">
            <div class="detail-label">Actividad / Curso</div>
            <div class="detail-value">{{ $enrollment->training->course->title }}</div>
        </div>

        <div class="detail-item">
            <div class="detail-label">Especialidad</div>
            <div class="detail-value">{{ $enrollment->training->course->specialty->specialty ?? 'General' }}</div>
        </div>
        
        <div class="row g-2">
            <div class="col-6">
                <div class="detail-item">
                    <div class="detail-label">Duración</div>
                    <div class="detail-value">{{ $enrollment->training->course->hours_count }} horas</div>
                </div>
            </div>
            <div class="col-6">
                <div class="detail-item">
                    <div class="detail-value text-center fs-4 text-success" style="padding-top: 25px;">
                        <span class="badge bg-success">NOTA: {{ $averageGrade }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-item mt-3">
            <div class="detail-label">Fecha de Registro</div>
            <div class="detail-value">{{ $enrollment->enrollment_date->format('d/m/Y') }}</div>
        </div>
        
        <div class="mt-4">
            <a href="/" class="btn btn-outline-light btn-sm px-4 rounded-pill">Ir a Inicio</a>
        </div>
    </div>

</body>
</html>
