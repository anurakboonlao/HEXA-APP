<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>www.dekdevelop.com</title>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/AdminLTE-2.4.2/bower_components/font-awesome/css/font-awesome.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="alert alert-info">
                    <h1>DEKDEVELOP</h1>
                </div>
                <br>
                <h2>ข้อมูลการเข้าใช้งานของท่าน</h2>
                <h4>Email : {{ $user->email }}</h4>
                <h4>New Password : {{ $password }} **กรุณา login แล้วเปลี่ยนรหัสผ่านใหม่เป็นของท่าน</h4>
                <br>
                <a href="{{ url('/') }}/admin" class="btn btn-success">เข้าสู่ระบบ</a>
            </div>
        </div>
    </div>
</body>
</html>