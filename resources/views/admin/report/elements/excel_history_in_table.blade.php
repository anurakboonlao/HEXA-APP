<table>
    <thead>
        <tr>
            <th>ลำดับที่</th>
            <th>วันที่นำเข้า</th>
            <th>ราคาซื้อ</th>
            <th>จำนวน</th>
            <th>อะไหล่</th>
            <th>เบอร์</th>
            <th>ใช้แทนรุ่น</th>
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
                    <td>
                        {{ date_thai($list->created_at) }}
                    </td>
                    <td>
                        {{ @$list->price_code }}
                    </td>
                    <td align="right">
                        {{ $list->qty }}
                    </td>
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