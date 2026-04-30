<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

    @include('components.navbar')

    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">Menú</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body p-0">
            @include('components.sidebar')
        </div>
    </div>

    <main class="main-layout">
        <div class="container-fluid px-2" style="padding-top: 68px;">
            <div class="row g-0">

                @if(!View::hasSection('noSidebar'))
                <aside class="col-lg-2 d-none d-lg-block pe-lg-0">
                    <div class="sidebar d-flex flex-column h-100">
                        @include('components.sidebar')
                    </div>
                </aside>
                @endif

                <section class="@if(View::hasSection('noSidebar')) col-12 @else col-12 col-lg-10 @endif p-4">
                    @yield('content')
                </section>

            </div>
        </div>
    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>