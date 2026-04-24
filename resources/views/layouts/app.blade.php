<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

    @include('components.navbar')

    <div class="container-fluid" style="padding-top: 76px;">
        <div class="row">

            <!-- SIDEBAR -->
            @if(!View::hasSection('noSidebar'))
                <div class="col-lg-2 d-none d-lg-block">
                    @include('components.sidebar')
                </div>
            @endif

            <!-- CONTENIDO -->
            <div class="@if(View::hasSection('noSidebar')) col-12 @else col-12 col-lg-10 @endif p-4">
                @yield('content')
            </div>

        </div>
    </div>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>