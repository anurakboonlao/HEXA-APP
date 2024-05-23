<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export hexa reward</title>
    <style>
        table {
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <table border="1">
        <tr class="bg-primary">
            <td>ลำดับ</td>
            <td>วันที่</td>
            <td>ทันตแพทย์</td>
            <td>คลินิก/โรงพยาบาล</td>
            <td>คะแนนที่ใช้</td>
            <td>ของรางวัลที่ต้องการแลก</td>
            <td>มูลค่าของรางวัล</td>
            <td>จำนวน</td>
            <td>คะแนนคงเหลือ</td>
            <td>สถานะที่จัดส่งของรางวัล</td>
            <td>จังหวัด</td>
        </tr>
        @foreach ($redemptions as $key => $redemption)
            <tr>
                <td>{{ ($key + 1) }}</td>
                <td>{{ set_date_format($redemption->created_at) }}</td>
                <td>{{ ($redemption->member->type == 'doctor') ? $redemption->member->name ?? '' : '' }}</td>
                <td>{{ $redemption->clinic }}</td>
                <td>{{ $redemption->point }}</td>
                <td>{{ $redemption->voucher->name ?? '' }}</td>
                <td>{{ $redemption->amount }}</td>
                <td>1</td>
                <td>N/A</td>
                <td>{{ $redemption->address ?? '' }}</td>
                <td>{{ $redemption->province ?? '' }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>