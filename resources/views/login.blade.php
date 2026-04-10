<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Systematic</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .brand {
            color: #0d6efd;
            font-weight: 700;
        }

        .btn-primary {
            border-radius: 12px;
            padding: 12px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
        }
    </style>
</head>

<body>

    <div class="container d-flex align-items-center justify-content-center min-vh-100">

        <div class="col-md-5">

            <div class="text-center mb-4">
                <img src="{{ asset('images/Systematic_logo.png') }}" width="140">
                <h4 class="mt-3 brand">Bienvenido a Systematic</h4>
                <p class="text-muted">Inicia sesión para continuar</p>
            </div>

            <div class="card login-card p-4">

                <form method="POST" action="/login">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Correo</label>
                        <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com">
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="********">
                    </div>

                    <!-- Button -->
                    <button type="submit" class="btn btn-primary w-100">
                        Iniciar Sesión
                    </button>

                </form>

            </div>

            <p class="text-center mt-3 text-muted">
                ¿No tienes cuenta? <a href="#" class="text-primary">Regístrate</a>
            </p>

        </div>

    </div>

</body>

</html>