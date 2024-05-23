<table>
    <thead>
        <tr>
            <th>ลำดับที่</th>
            <th>วันที่ขาย</th>
            <th>ราคาขาย</th>
            <th>จำนวน</th>
            <th>ประเภท</th>
            <th>อ้างอิงเลขที่</th>
            <th>อะไหล่</th>
            <th>เบอร์</th>
            <th>ใช้แทนรุ่น</th>
            <th>ลูกค้า</th>
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
                <td align="right">
                    {{ number_format($list->price) }}
                </td>
                <td align="right">{{ $list->qty }}</td>
                <td>
                    @if ($list->invoice->type == 'a')
                        บิลเงินสด/ใบเสร็จรับเงิน
                    @else
                        ใบส่งของ
                    @endif
                </td>
                <td>
                    {{ $list->invoice->id }}
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
                    {{ @$list->invoice->customer_name }}
                </td>
            </tr>
        @endif
        @endforeach
    </tbody>
</table>