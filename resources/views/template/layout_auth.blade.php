<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FIKOM EVENTS | {{ $title }}</title>

    <link rel="icon" href="data:;base64,=">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/dist/css/adminlte.min.css">
    <!-- jQuery -->
    <script src="{{ asset('adminlte') }}/plugins/jquery/jquery.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/toastr/toastr.min.css">
    <script src="{{ asset('adminlte') }}/plugins/toastr/toastr.min.js"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('adminlte') }}/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/jquery-validation/additional-methods.min.js"></script>
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/gritter/css/jquery.gritter.min.css') }}">
</head>

<body class="hold-transition login-page">
    @yield('main')
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte') }}/dist/js/adminlte.min.js"></script>
    <script src="{{ asset('adminlte/plugins/gritter/js/jquery.gritter.min.js') }}"></script>
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
    </script>
</body>

</html>
