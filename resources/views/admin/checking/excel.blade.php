<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Order Pickups</title>
</head>
<body>
    <table class="table table-bordered">
        <tr class="bg-primary">
            <td>วันเวลาบันทึก</td>
            <td>พนักงาน</td>
            <td>เวลาที่กรอก</td>
            <td>สถานที่</td>
            <td>GPS</td>
        </tr>
            @foreach ($checkings as $checking)
            <tr>
                <td>{{ $checking->created_at }}</td>
                <td>{{ $checking->member->name ?? '' }}</td>
                <td>
                    {{ $checking->time }}
                </td>
                <td>{{ $checking->location }}</td>
                <td>
                    {{ $checking->lat }},{{ $checking->long }}
                </td>
            </tr>
            @endforeach
    </table>
</body>
</html>