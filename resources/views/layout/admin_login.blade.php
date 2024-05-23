<!DOCTYPE html>
<html>
<head>
    
    <base href="{{ url('/') }}/">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Hexa Ceram</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/AdminLTE-2.4.2/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="assets/AdminLTE-2.4.2/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/AdminLTE-2.4.2/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="assets/AdminLTE-2.4.2/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
<body class="hold-transition login-page">
    @yield('content')
    
    <!-- /.login-box -->
    <!-- jQuery 3 -->
    <script src="assets/AdminLTE-2.4.2/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="bower_components/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <!-- iCheck -->
    <script src="assets/AdminLTE-2.4.2/plugins/iCheck/icheck.min.js"></script>
    <script>
    $(function () {
        $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
        });
    });
    </script>
    @if (session('message'))
        @php
            $message = session('message');
        @endphp
        <script>
            $(function(e) {
                swal({
                    type: "{{ ($message['status']) ? 'success' : 'error' }}",
                    title: "{{ $message['message'] }}",
                    showConfirmButton: true,
                    timer: false
                })
            });
        </script>
    @endif
</body>
</html>