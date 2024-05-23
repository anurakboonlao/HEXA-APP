<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>supply orders</title>
</head>
    <body>
        <table border="1">
            <tr class="bg-primary">
                <td>วันที่</td>
                <td>รหัส</td>
                <td>สินค้า</td>
                <td>ผู้สั่งซื้อ</td>
                <td>ยอดเงินที่ต้องชำระ</td>
                <td>สถานะ</td>
            </tr>
            @foreach ($orders as $order)
            <tr>
                <td>{{ set_date_format($order->date) }}</td>
                <td>{{ order_number($order->id) }}</td>
                <td>
                    @foreach ($order->products ?? [] as $item)
                        {{ $item->product->name ?? '' }} จำนวน {{ $item->qty ?? '' }} สี {{ $item->color ?? '' }} ไซต์ {{ $item->size ?? '' }} <br>
                    @endforeach
                </td>
                <td>{{ $order->member->name ?? null }}</td>
                <td>{{ $order->total }}</td>
                <td>{!! order_status(($order->status) ? 'success' : 'new') !!}</td>
            </tr>
            @endforeach
        </table>
    </body>
</html>