<div class="table-responsive">
    <form action="">
        <table class="table">
            <thead>
                <th align="center">เลขที่บิล</th>
                <th align="center">หมายเลขบิล</th>
                <th align="center">วันครบกำหนดชำระเงิน</th>
                <th align="center">จำนวนเงิน</th>
            </thead>
            <tbody>
                @foreach ($bills as $bill)
                <tr>
                    <td>{{ $bill['id'] }}</td>
                    <td>{{ $bill['number'] }}</td>
                    <td>{{ $bill['due_date'] }}</td>
                    <td align="right">{{ @number_format($bill['total_amount'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <td></td>
                <td></td>
                <td>ยอดที่ชำระเงินทั้งหมด</td>
                <td align="right">{{ @number_format($total_amount, 2) }}</td>
            </tfoot>
        </table>
        <div class="form-group text-center">
            <span class="text-danger">"ยอดเงินที่เป็นหลักทศนิยมทางระบบจัดทำการปัดขึ้นให้เป็นตัวเลขถ้วนๆ เนื่องจากระบบไม่รองรับการชำระเงินที่มีหลักทศนิยม <br> ท่านยินยอมที่จะชำระเงินตามเงื่อนไขใช่ไหม ?"</span> <br><br>
            @if ($total_amount >= 1)
                {{-- <a href="{{ $data['payment_url'] }}" class="btn btn-lg btn-primary">ยืนยันการชำระเงินผ่าน 2c2p</a> --}}
                <a href="{{ $data['transfer_url'] }}" class="btn btn-lg" style="background-color: #0071ba;border-color: #0071ba;color: #FFFFFF">ชำระเงิน</a>
            @endif
        </div>
    </form>
</div>