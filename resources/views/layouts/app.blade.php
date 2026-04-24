<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    @stack('styles')
</head>

<body>

    @include('components.navbar')

    <div class="container-fluid" style="padding-top: 76px;">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-lg-2 d-none d-lg-block">
                @if(!View::hasSection('noSidebar'))
                    @include('components.sidebar')
                @endif
            </div>

            <!-- Contenido -->
            <div class="col-12 col-lg-7 p-4">
                @yield('content')
            </div>

            <!-- Right Sidebar -->
            <div class="col-xl-3 d-none d-xl-block">
                @include('components.right-sidebar')
            </div>

        </div>
    </div>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>