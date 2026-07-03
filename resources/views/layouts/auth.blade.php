<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auth')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        .btn,
        .btn-primary,
        .btn-secondary,
        .btn-success,
        .btn-danger,
        .btn-info,
        .btn-warning,
        .btn-light,
        .btn-dark,
        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-outline-success,
        .btn-outline-danger,
        .btn-outline-info,
        .btn-outline-warning,
        .btn-outline-light,
        .btn-outline-dark {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            line-height: 1.4 !important;
            border-radius: 0.2rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.25rem !important;
            text-decoration: none !important;
        }

        .btn:hover,
        .btn:focus,
        .btn:active,
        .btn:focus-visible,
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active,
        .btn-primary:focus-visible,
        .btn-secondary:hover,
        .btn-secondary:focus,
        .btn-secondary:active,
        .btn-secondary:focus-visible,
        .btn-success:hover,
        .btn-success:focus,
        .btn-success:active,
        .btn-success:focus-visible,
        .btn-danger:hover,
        .btn-danger:focus,
        .btn-danger:active,
        .btn-danger:focus-visible,
        .btn-info:hover,
        .btn-info:focus,
        .btn-info:active,
        .btn-info:focus-visible,
        .btn-warning:hover,
        .btn-warning:focus,
        .btn-warning:active,
        .btn-warning:focus-visible,
        .btn-light:hover,
        .btn-light:focus,
        .btn-light:active,
        .btn-light:focus-visible,
        .btn-dark:hover,
        .btn-dark:focus,
        .btn-dark:active,
        .btn-dark:focus-visible,
        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active,
        .btn-outline-primary:focus-visible,
        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus,
        .btn-outline-secondary:active,
        .btn-outline-secondary:focus-visible,
        .btn-outline-success:hover,
        .btn-outline-success:focus,
        .btn-outline-success:active,
        .btn-outline-success:focus-visible,
        .btn-outline-danger:hover,
        .btn-outline-danger:focus,
        .btn-outline-danger:active,
        .btn-outline-danger:focus-visible,
        .btn-outline-info:hover,
        .btn-outline-info:focus,
        .btn-outline-info:active,
        .btn-outline-info:focus-visible,
        .btn-outline-warning:hover,
        .btn-outline-warning:focus,
        .btn-outline-warning:active,
        .btn-outline-warning:focus-visible,
        .btn-outline-light:hover,
        .btn-outline-light:focus,
        .btn-outline-light:active,
        .btn-outline-light:focus-visible,
        .btn-outline-dark:hover,
        .btn-outline-dark:focus,
        .btn-outline-dark:active,
        .btn-outline-dark:focus-visible {
            box-shadow: none !important;
            outline: none !important;
            transform: none !important;
        }

        .btn-primary,
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active,
        .btn-primary:focus-visible {
            background-color: #4e73df !important;
            border-color: #4e73df !important;
            color: #fff !important;
        }

        .btn-secondary,
        .btn-secondary:hover,
        .btn-secondary:focus,
        .btn-secondary:active,
        .btn-secondary:focus-visible {
            background-color: #858796 !important;
            border-color: #858796 !important;
            color: #fff !important;
        }

        .btn-success,
        .btn-success:hover,
        .btn-success:focus,
        .btn-success:active,
        .btn-success:focus-visible {
            background-color: #1cc88a !important;
            border-color: #1cc88a !important;
            color: #fff !important;
        }

        .btn-danger,
        .btn-danger:hover,
        .btn-danger:focus,
        .btn-danger:active,
        .btn-danger:focus-visible {
            background-color: #e74a3b !important;
            border-color: #e74a3b !important;
            color: #fff !important;
        }

        .btn-info,
        .btn-info:hover,
        .btn-info:focus,
        .btn-info:active,
        .btn-info:focus-visible {
            background-color: #36b9cc !important;
            border-color: #36b9cc !important;
            color: #fff !important;
        }

        .btn-warning,
        .btn-warning:hover,
        .btn-warning:focus,
        .btn-warning:active,
        .btn-warning:focus-visible {
            background-color: #f6c23e !important;
            border-color: #f6c23e !important;
            color: #212529 !important;
        }

        .btn-light,
        .btn-light:hover,
        .btn-light:focus,
        .btn-light:active,
        .btn-light:focus-visible {
            background-color: #f8f9fc !important;
            border-color: #f8f9fc !important;
            color: #212529 !important;
        }

        .btn-dark,
        .btn-dark:hover,
        .btn-dark:focus,
        .btn-dark:active,
        .btn-dark:focus-visible {
            background-color: #5a5c69 !important;
            border-color: #5a5c69 !important;
            color: #fff !important;
        }

        .btn-outline-primary,
        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active,
        .btn-outline-primary:focus-visible {
            background-color: #4e73df !important;
            border-color: #4e73df !important;
            color: #fff !important;
        }

        .btn-outline-secondary,
        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus,
        .btn-outline-secondary:active,
        .btn-outline-secondary:focus-visible {
            background-color: #858796 !important;
            border-color: #858796 !important;
            color: #fff !important;
        }

        .btn-outline-success,
        .btn-outline-success:hover,
        .btn-outline-success:focus,
        .btn-outline-success:active,
        .btn-outline-success:focus-visible {
            background-color: #1cc88a !important;
            border-color: #1cc88a !important;
            color: #fff !important;
        }

        .btn-outline-danger,
        .btn-outline-danger:hover,
        .btn-outline-danger:focus,
        .btn-outline-danger:active,
        .btn-outline-danger:focus-visible {
            background-color: #e74a3b !important;
            border-color: #e74a3b !important;
            color: #fff !important;
        }

        .btn-outline-info,
        .btn-outline-info:hover,
        .btn-outline-info:focus,
        .btn-outline-info:active,
        .btn-outline-info:focus-visible {
            background-color: #36b9cc !important;
            border-color: #36b9cc !important;
            color: #fff !important;
        }

        .btn-outline-warning,
        .btn-outline-warning:hover,
        .btn-outline-warning:focus,
        .btn-outline-warning:active,
        .btn-outline-warning:focus-visible {
            background-color: #f6c23e !important;
            border-color: #f6c23e !important;
            color: #212529 !important;
        }

        .btn-outline-light,
        .btn-outline-light:hover,
        .btn-outline-light:focus,
        .btn-outline-light:active,
        .btn-outline-light:focus-visible {
            background-color: #f8f9fc !important;
            border-color: #f8f9fc !important;
            color: #212529 !important;
        }

        .btn-outline-dark,
        .btn-outline-dark:hover,
        .btn-outline-dark:focus,
        .btn-outline-dark:active,
        .btn-outline-dark:focus-visible {
            background-color: #5a5c69 !important;
            border-color: #5a5c69 !important;
            color: #fff !important;
        }
    </style>
</head>

<body>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>