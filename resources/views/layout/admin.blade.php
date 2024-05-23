<!DOCTYPE html>
<html>
<head>
<base href="{{ env('base_url', url('/')) . '/' }}">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>HEXA CERAM - ADMIN</title>
<meta name="robots" content="noindex">
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="assets/AdminLTE-2.4.2/bower_components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="assets/AdminLTE-2.4.2/bower_components/Ionicons/css/ionicons.min.css">
<!-- daterange picker -->
<link rel="stylesheet" href="assets/AdminLTE-2.4.2/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="assets/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="assets/AdminLTE-2.4.2/dist/css/AdminLTE.min.css">
<link rel="stylesheet" href="bower_components/sweetalert2/dist/sweetalert2.min.css">
<link rel="stylesheet" href="assets/AdminLTE-2.4.2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="assets/AdminLTE-2.4.2/dist/css/skins/skin-black.min.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
    input, select, .btn, .form-control {
        border-radius: 2px;
    }
    
    body {
        font-size: 14px;
    }
    
    .table thead th, .table tbody tr td {
        padding: 5px;
    }
    
    input[type=checkbox] {
        zoom: 1;
    }

    [contenteditable] { padding: 5px; outline: 0px solid transparent; border-radius: 3px; background-color: #e3e3e3;}
    [contenteditable]:not(:focus) { border: 1px dashed #ddd; }
    [contenteditable]:focus { border: 1px solid #51a7e8; box-shadow: inset 0 1px 2px rgba(0,0,0,0.075),0 0 5px rgba(81,167,232,0.5); }

    .fa {
        cursor: pointer;
    }

    .treeview-menu > li > a {
        font-size: 13px;
    }
</style>

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-black sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

    @include('admin.elements.header')

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    @include('admin.elements.sidebar')

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <section class="content-header">
    @yield('content-header')
    </section>

    <!-- Main content -->
    <section class="content">
        @yield('content')
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 0.0.1 reserved.
        <br><br>
    </footer>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
        immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="assets/AdminLTE-2.4.2/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="assets/AdminLTE-2.4.2/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/AdminLTE-2.4.2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="assets/AdminLTE-2.4.2/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- date-range-picker -->
    <script src="assets/AdminLTE-2.4.2/bower_components/moment/min/moment.min.js"></script>
    <script src="assets/AdminLTE-2.4.2/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap datepicker -->
    <script src="assets/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <!-- bootstrap time picker -->
    <script src="assets/AdminLTE-2.4.2/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <!-- FastClick -->
    <script src="assets/AdminLTE-2.4.2/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="bower_components/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="bower_components/gasparesganga-jquery-loading-overlay/src/loadingoverlay.min.js"></script>
    <script src="bower_components/gasparesganga-jquery-loading-overlay/extras/loadingoverlay_progress/loadingoverlay_progress.min.js"></script>
    <script type="text/javascript" src="bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script> 
    <!-- AdminLTE App -->
    <script src="assets/AdminLTE-2.4.2/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="assets/AdminLTE-2.4.2/dist/js/demo.js"></script>
    <script src="js/admin/app.js"></script>
    <script>
        var options = {
            filebrowserImageBrowseUrl: 'laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: 'laravel-filemanager/upload?type=Images&_token=',
            filebrowserBrowseUrl: 'laravel-filemanager?type=Files',
            filebrowserUploadUrl: 'laravel-filemanager/upload?type=Files&_token='
        };
        
        $(document).ready(function () {
            $('.sidebar-menu').tree()
        })

    </script>
    @yield('script')
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