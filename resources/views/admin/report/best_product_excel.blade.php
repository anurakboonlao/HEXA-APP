<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>สินค้าขายดี</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>ลำดับที่</th>
                <th>จำนวนที่ขายได้</th>
                <th>อะไหล่</th>
                <th>เบอร์</th>
                <th>ใช้แทนรุ่น</th>
                <th>ราคาซื้อ</th>
                <th>ราคาขาย</th>
                <th>จำนวนอะไหล่คงเหลือ</th>
                <th>นำเข้าจาก</th>
                <th>สถานที่เก็บ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $key => $list)
            @php
                $product = \App\Product::find($list->product_id);
            @endphp
                @if ($product)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $list->total_qty }}</td>
                        <td>
                            {{ product_name($product) }}
                        </td>
                        <td>
                            {{ @$product->number }}
                        </td>
                        <td>
                            {{ @$product->sub_version }}
                        </td>
                        <td>
                            {{ @$product->buy_price_code }}
                        </td>
                        <td>
                            {{ @$product->sale_price_code }}
                        </td>
                        <td align="right">
                            {{ @number_format($product->qty) }}
                        </td>
                        <td>
                            {{ @$product->in_from }}
                        </td>
                        <td>
                            {{ @$product->location }}
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>