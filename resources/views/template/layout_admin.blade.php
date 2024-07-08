@php
    $secondSegment = Request::segment(2);
    $thridSegment = Request::segment(3);
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADMIN | {{ $title }}</title>

    <link rel="icon" href="https://fikom.udb.ac.id/themes/fakultas-udb/assets/img/logo.png">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/dist/css/adminlte.min.css">
    <!-- jQuery -->
    <script src="{{ asset('adminlte') }}/plugins/jquery/jquery.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/gritter/css/jquery.gritter.min.css') }}">
    <!-- jquery-validation -->
    <script src="{{ asset('adminlte') }}/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/jquery-validation/additional-methods.min.js"></script>
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="" class="brand-link">
                <img src="https://fikom.udb.ac.id/themes/fakultas-udb/assets/img/logo.png" alt="Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">FIKOM EVENT</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image mr-2">
                        <i class="fa fa-user fa-2x text-white"></i>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->nama }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ url('admin/dashboard') }}"
                                class="nav-link {{ $secondSegment == 'dashboard' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        <li class="nav-item {{ $secondSegment == 'users' ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-users"></i>
                                <p>
                                    Users
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('admin/users/admin') }}"
                                        class="nav-link {{ $thridSegment == 'admin' ? 'active' : '' }}">
                                        <i class="fa fa-user-tie nav-icon"></i>
                                        <p>Admin</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/users/pendaftar') }}"
                                        class="nav-link {{ $thridSegment == 'pendaftar' ? 'active' : '' }}">
                                        <i class="fa fa-user nav-icon"></i>
                                        <p>Pendaftar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('admin/users/create') }}"
                                        class="nav-link {{ $thridSegment == 'create' ? 'active' : '' }}">
                                        <i class="fa fa-user-edit nav-icon"></i>
                                        <p>Tambah User</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('admin/events') }}"
                                class="nav-link {{ $secondSegment == 'events' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar"></i>
                                <p>
                                    Events
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" onclick="logout();" class="nav-link">
                                <i class="nav-icon fa fa-sign-out-alt"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                @yield('header')
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                @yield('main')
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="https://fikom.udb.ac.id/" target="_blank">FIKOM
                    UDB</a>.</strong> All
            rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte') }}/dist/js/adminlte.min.js"></script>
    <script src="{{ asset('adminlte/plugins/gritter/js/jquery.gritter.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootbox/bootbox.min.js') }}"></script>
    @stack('scripts')

    <script>
        $(document).ready(function() {
            @if (session('status'))
                @php
                    $status = session('status');
                    [$statusType, $message] = explode('#', $status);
                @endphp
                $.gritter.add({
                    title: '{{ $statusType }}!',
                    text: '{{ $message }}',
                    class_name: 'gritter-{{ $statusType }}',
                    time: 3000,
                });
            @endif
        });

        function logout() {
            bootbox.confirm({
                buttons: {
                    confirm: {
                        label: '<i class="fa fa-check"></i>',
                        className: 'btn-danger'
                    },
                    cancel: {
                        label: '<i class="fa fa-undo"></i>',
                        className: 'btn-default'
                    },
                },
                title: "Yakin ingin LogOut?",
                message: "Anda akan keluar dari sesi ini!",
                callback: function(result) {
                    if (result) {
                        document.location = "{{ url('/logout') }}";
                    }
                }
            });
        }
    </script>
</body>

</html>
