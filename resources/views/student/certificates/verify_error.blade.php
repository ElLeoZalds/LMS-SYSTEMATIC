<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Verificación - LMS-SYSTEMATIC</title>
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
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .error-icon {
            font-size: 64px;
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            width: 100px;
            height: 100px;
            line-height: 100px;
            border-radius: 50%;
            margin: 0 auto 24px;
            border: 2px solid rgba(239, 68, 68, 0.2);
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
        
        <div class="error-icon">
            <i class="bi bi-shield-x"></i>
        </div>
        
        <h3 class="fw-bold mb-3">Error de Verificación</h3>
        <p class="text-muted mb-4">{{ $message }}</p>
        
        <div class="mt-4">
            <a href="/" class="btn btn-outline-light btn-sm px-4 rounded-pill">Ir a Inicio</a>
        </div>
    </div>

</body>
</html>
