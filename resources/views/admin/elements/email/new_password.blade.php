<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hexa Ceram</title>
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/AdminLTE-2.4.2/bower_components/font-awesome/css/font-awesome.min.css">
</head>
<body>

    <div style="width: 700px;">
        <br>
        <h2>เรียน คุณหมอที่เคารพ</h2>
        <br>
        <p>
            ยินดีต้อนรับเข้าสู่ Hexa Ceram  Application    ตัวช่วยจะทำให้การทำงานของคุณหมอง่ายขึ้น <br>
            ด้วย Feature ที่ให้คุณหมอเข้าถึงข้อมูลทุกอย่างได้ตลอด 24 ชั่วโมง <br>
            คุณหมอสามารถใช้ Username  และ Password  สำหรับ Log in เข้าใช้ Application
        </p>

        <br>

        <h4>Username: {{ $member->username }}</h4>
        <h4>Password: {{ $password }}</h4>

        @php
            $downloadUrl = url('/') ."/files/app_guide.pdf";
        @endphp
        <br>
        <a href="{{ $downloadUrl }}">
            <img src="{{ url('/') }}/images/icon_download.png" width="200" alt="">
        </a>

        <br><br>
        <h4>ขอแสดงความนับถือ <br>
            บริษัท เอ็กซา ซีแลม จำกัด</h4>

        <br><br>
        <img src="{{ url('/') }}/images/app_email-04.jpg" width="500" alt="">
        <br>
    </div>
</body>
</html>