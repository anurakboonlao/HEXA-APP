<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Payments</title>
</head>
<body>
    <table border="1">
        <tr>
            <td>รหัส</td>
            <td>ผู้จ่าย</td>
            <td>วันที่</td>
            <td>จำนวนเงิน</td>
            <td>รายละเอียดการชำระเงิน</td>
            <td>จำนวนที่ชำระ</td>
            <td>สถานะ</td>
            <td>ผู้ยืนยัน</td>
        </tr>
        @foreach ($payments as $key => $payment)
        <tr>
            <td>{{ ($payment->id) }}</td>
            <td>
                {{ $payment->member->name ?? '' }}
            </td>
            <td>{{ set_date_format($payment->created_at) }}</td>
            <td align="right">{{ $payment->total }}</td>
            <td>
                Online / ref {{ $payment->ref1 }}
            </td>
            <td align="right">{{ $payment->total }}</td>
            {{-- <td>{!! redemption_status($payment->is_success) !!}</td> --}}
            <td>{!! redemption_status($payment) !!}</td>
            <td align="right">{{ $payment->approved_by->name ?? '' }}</td>
        </tr>
        @endforeach
        <tr class="bg-success">
            <td align="right" colspan="5">รวม</td>
            <td align="right">{{ @number_format($payments->sum('total'), 2) }}</td>
            <td colspan="2"></td>
        </tr>
    </table>
</body>
</html>