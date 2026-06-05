<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'Systematic LMS')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        .bg-avatar-1 {
            background: #4e73df;
        }

        .bg-avatar-2 {
            background: #1cc88a;
        }

        .bg-avatar-3 {
            background: #f6c23e;
        }

        .bg-avatar-4 {
            background: #e74a3b;
        }

        .btn {
            transition: none !important;
            margin: 0.125rem 0.125rem 0.125rem 0 !important;
        }
        .btn:last-child {
            margin-right: 0 !important;
        }
        .btn:focus,
        .btn:active,
        .btn.focus {
            box-shadow: none !important;
            outline: none !important;
        }
        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active,
        .btn-primary.focus {
            background-color: #4e73df !important;
            border-color: #4e73df !important;
            color: #fff !important;
        }
        .btn-secondary:hover,
        .btn-secondary:focus,
        .btn-secondary:active,
        .btn-secondary.focus {
            background-color: #858796 !important;
            border-color: #858796 !important;
            color: #fff !important;
        }
        .btn-success:hover,
        .btn-success:focus,
        .btn-success:active,
        .btn-success.focus {
            background-color: #1cc88a !important;
            border-color: #1cc88a !important;
            color: #fff !important;
        }
        .btn-danger:hover,
        .btn-danger:focus,
        .btn-danger:active,
        .btn-danger.focus {
            background-color: #e74a3b !important;
            border-color: #e74a3b !important;
            color: #fff !important;
        }
        .btn-info:hover,
        .btn-info:focus,
        .btn-info:active,
        .btn-info.focus {
            background-color: #36b9cc !important;
            border-color: #36b9cc !important;
            color: #fff !important;
        }
        .btn-warning:hover,
        .btn-warning:focus,
        .btn-warning:active,
        .btn-warning.focus {
            background-color: #f6c23e !important;
            border-color: #f6c23e !important;
            color: #212529 !important;
        }
        .btn-light:hover,
        .btn-light:focus,
        .btn-light:active,
        .btn-light.focus {
            background-color: #f8f9fc !important;
            border-color: #f8f9fc !important;
            color: #212529 !important;
        }
        .btn-dark:hover,
        .btn-dark:focus,
        .btn-dark:active,
        .btn-dark.focus {
            background-color: #5a5c69 !important;
            border-color: #5a5c69 !important;
            color: #fff !important;
        }
        .btn-outline-primary,
        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active,
        .btn-outline-primary.focus {
            background-color: #4e73df !important;
            border-color: #4e73df !important;
            color: #fff !important;
        }
        .btn-outline-secondary,
        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus,
        .btn-outline-secondary:active,
        .btn-outline-secondary.focus {
            background-color: #858796 !important;
            border-color: #858796 !important;
            color: #fff !important;
        }
        .btn-outline-success,
        .btn-outline-success:hover,
        .btn-outline-success:focus,
        .btn-outline-success:active,
        .btn-outline-success.focus {
            background-color: #1cc88a !important;
            border-color: #1cc88a !important;
            color: #fff !important;
        }
        .btn-outline-danger,
        .btn-outline-danger:hover,
        .btn-outline-danger:focus,
        .btn-outline-danger:active,
        .btn-outline-danger.focus {
            background-color: #e74a3b !important;
            border-color: #e74a3b !important;
            color: #fff !important;
        }
        .btn-outline-info,
        .btn-outline-info:hover,
        .btn-outline-info:focus,
        .btn-outline-info:active,
        .btn-outline-info.focus {
            background-color: #36b9cc !important;
            border-color: #36b9cc !important;
            color: #fff !important;
        }
        .btn-outline-warning,
        .btn-outline-warning:hover,
        .btn-outline-warning:focus,
        .btn-outline-warning:active,
        .btn-outline-warning.focus {
            background-color: #f6c23e !important;
            border-color: #f6c23e !important;
            color: #212529 !important;
        }
        .btn-outline-light,
        .btn-outline-light:hover,
        .btn-outline-light:focus,
        .btn-outline-light:active,
        .btn-outline-light.focus {
            background-color: #f8f9fc !important;
            border-color: #f8f9fc !important;
            color: #212529 !important;
        }
        .btn-outline-dark,
        .btn-outline-dark:hover,
        .btn-outline-dark:focus,
        .btn-outline-dark:active,
        .btn-outline-dark.focus {
            background-color: #5a5c69 !important;
            border-color: #5a5c69 !important;
            color: #fff !important;
        }

        .table-borderless tr {
            padding: 0.5rem 0;
        }

        .table-borderless .border-bottom {
            border-bottom: 1px solid #dee2e6 !important;
        }

        @media print {
            html, body {
                background: #fff !important;
                color: #000 !important;
            }

            #wrapper,
            .sidebar,
            .topbar,
            .sticky-footer,
            .scroll-to-top,
            .navbar-nav,
            .nav-tabs,
            .nav-link,
            .dropdown,
            .dropdown-menu,
            .btn,
            .btn-outline-secondary,
            .btn-outline-success,
            .btn-outline-primary,
            .badge,
            .card .btn,
            .alert,
            .pagination,
            .modal,
            .modal-backdrop {
                display: none !important;
                visibility: hidden !important;
            }

            .card,
            .card-header,
            .card-body,
            .container-fluid,
            #content,
            #content-wrapper {
                box-shadow: none !important;
                border: none !important;
                background: transparent !important;
            }

            .container-fluid {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 11pt !important;
            }

            table th,
            table td {
                border: 1px solid #000 !important;
                padding: 0.5rem !important;
            }

            thead {
                display: table-header-group !important;
            }

            tfoot {
                display: table-footer-group !important;
            }

            tr {
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @auth
            @unless(View::hasSection('noSidebar'))
                @include('components.sidebar')
            @endunless
        @endauth

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                @auth
                    @unless(View::hasSection('noSidebar'))
                        @include('components.navbar')
                    @endunless
                @endauth

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    {{-- ALERTAS TOAST CON SWEETALERT --}}
                    @if(session('success'))
                        <script>
                            (function() {
                                const short = {{ session('short_toast') ? 'true' : 'false' }};
                                const timerValue = short ? 1400 : 3000;
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: '{{ session('success') }}',
                                    showConfirmButton: false,
                                    timer: timerValue,
                                    timerProgressBar: true,
                                    backdrop: false,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                });
                            })();
                        </script>
                    @endif

                    @if(session('error'))
                        <script>
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: '{{ session('error') }}',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                backdrop: false,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });
                        </script>
                    @endif

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            @include('components.footer')

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    @stack('scripts')
</body>

</html>