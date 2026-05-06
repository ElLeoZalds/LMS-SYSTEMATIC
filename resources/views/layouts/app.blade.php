<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Systematic LMS')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            color: white;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .bg-avatar-1 { background: #4e73df; }
        .bg-avatar-2 { background: #1cc88a; }
        .bg-avatar-3 { background: #f6c23e; }
        .bg-avatar-4 { background: #e74a3b; }
        .table-borderless tr {
            padding: 0.5rem 0;
        }
        .table-borderless .border-bottom {
            border-bottom: 1px solid #dee2e6 !important;
        }
    </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    {{-- NAVBAR --}}
    @include('components.navbar')

    <div class="flex-grow-1" style="padding-top: 70px;">

        <div class="container-fluid">
            <div class="row g-0">

                {{-- SIDEBAR DESKTOP --}}
                @auth
                    @unless(View::hasSection('noSidebar'))
                        <aside class="col-lg-2 d-none d-lg-block bg-white border-end">
                            <div class="h-100 p-3">
                                @include('components.sidebar')
                            </div>
                        </aside>
                    @endunless
                @endauth

                {{-- CONTENIDO --}}
                <main class="@auth @unless(View::hasSection('noSidebar')) col-lg-10 @endunless @endauth col-12 p-4">

                    {{-- ALERTAS --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')

                </main>

            </div>
        </div>

    </div>

    {{-- FOOTER --}}
    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>