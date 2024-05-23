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
            <td>คลีนิค</td>
            <td>วัน เวลา</td>
            <td>สถานที่</td>
            <td>สถานะ</td>
            <td>ผู้รับงาน</td>
            <td>เวลารับงาน</td>
            <td>GPS</td>
        </tr>
        @foreach ($pickups as $pickup)
            <tr>
                <td>{{ $pickup->member->name ?? '' }}</td>
                <td>
                    {{ $pickup->time }}
                    {{ set_date_format($pickup->created_at) }}
                </td>
                <td>{{ $pickup->address }}</td>
                <td>{!! pickup_status($pickup->checked) !!}</td>
                <td>
                    {{ $pickup->sale->name ?? '' }}
                </td>
                <td>
                    {{ ($pickup->checked) ? $pickup->updated_at : '' }}
                </td>
                <td>{{ $pickup->lat }},{{ $pickup->long }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>