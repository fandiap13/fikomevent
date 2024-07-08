<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FIKOM EVENT | {{ $title }}</title>
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
    <script src="{{ asset('adminlte') }}/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/jquery-validation/additional-methods.min.js"></script>
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/gritter/css/jquery.gritter.min.css') }}">
    @stack('styles')
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-dark">
            <div class="container">
                <a href="{{ asset('adminlte') }}/index3.html" class="navbar-brand">
                    <img src="https://fikom.udb.ac.id/themes/fakultas-udb/assets/img/logo.png" alt="Logo"
                        class="brand-image img-circle">
                    <span class="brand-text font-weight-light">FIKOM EVENT</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="{{ url('/') }}" class="nav-link">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/events') }}" class="nav-link">Events</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/tentang-kami') }}" class="nav-link">Tentang Kami</a>
                        </li>
                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    @if (Auth::user())
                        <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" class="nav-link dropdown-toggle"><i class="fas fa-user mr-2"></i>
                                {{ Auth::user()->nama }}</a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                @if (Auth::user()->role == 'admin')
                                    <li><a href="{{ url('admin/dashboard') }}" class="dropdown-item"><i
                                                class="fas fa-tachometer-alt"></i> Dashboard</a>
                                    </li>
                                @else
                                    <li><a href="{{ url('/profil') }}" class="dropdown-item"><i
                                                class="fa fa-user-edit"></i> Profil</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ url('/my-events') }}" class="dropdown-item"><i
                                            class="fa fa-calendar"></i> Event Yang Dikuti</a>
                                </li>
                                <li><a href="#" onclick="logout()" class="dropdown-item"><i
                                            class="fa fa-sign-out-alt"></i>
                                        LogOut</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ url('auth/login') }}" class="btn btn-primary"><i
                                    class="fas fa-sign-in-alt mr-2"></i>
                                Login</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                @yield('header')
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    @yield('main')
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer bg-dark">
            <strong>Copyright &copy; {{ date('Y') }} <a href="https://fikom.udb.ac.id/" target="_blank">FIKOM
                    UDB</a>.</strong> All
            rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->


    <script src="{{ asset('adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
                title: "Yakin ingin Log Out?",
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
