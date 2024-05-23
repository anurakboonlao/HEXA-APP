<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $bill['number'] }}</title>
    <link rel="stylesheet" href="{{ url('/') }}/template/AdminLTE/bootstrap/css/bootstrap.min.css">
    <style>
        @font-face {
            font-family: 'thaisanslite';
            font-style: normal;
            font-weight: normal;
            src: url("{{ url('assets/fonts/thaisanslite/thaisanslite_r1.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'thaisanslite';
            font-style: normal;
            font-weight: bold;
            src: url("{{ url('assets/fonts/thaisanslite/thaisanslite_r1.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'thaisanslite';
            font-style: italic;
            font-weight: normal;
            src: url("{{ url('assets/fonts/thaisanslite/thaisanslite_r1.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'thaisanslite';
            font-style: italic;
            font-weight: bold;
            src: url("{{ url('assets/fonts/thaisanslite/thaisanslite_r1.ttf') }}") format('truetype');
        }

        body {
            font-size: 14px;
            font-family: 'thaisanslite';
            line-height: 14px;
        }

        .main {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table tr:first-child td {
            text-align: center;
        }

        td {
            vertical-align: bottom;
            padding: 2px;
        }
        
        h4 {
            font-family: 'thaisanslite';
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="main">
        <img src="{{ url('/') }}/images/bill-head.png" width="100%" alt="">
        <hr>
        <table border="1">
            <tr>
                <td align="left" colspan="2">เลขประจำตัวผู้เสียภาษี :  0 1055 38031 03 8 (00000)</td>
            </tr>
            <tr>
                <td width="60%">
                    ชื่อ/Name : {{ $bill['contact_id'][1] ?? '' }}
                    <br>
                    ที่อยู่/Address : {{ $bill['contact_address'][1] ?? '' }}
                    <br>
                    โทรศัพท์(Tel) : 
                    <br>
                    แฟกซ์ (Fax) : 
                    <br>
                </td>
                <td>
                    <br>
                    <span style="font-size: 24px;margin-left: 100px;">ใบวางบิล</span> <br><br>
                    วันที่/Date : {{ date('d/m/Y', strtotime($bill['date'] ?? '')) }}
                    <br>
                    เลขที่เอกสาร/No : {{ $bill['number'] ?? '' }}
                    <br>
                    รหัสอ้างอิง/Ref : {{ $bill['ref'] ?? '' }}
                </td>
            </tr>
        </table>
        <br>
        <table border="1">
            <tr>
                <td>
                    ลำดับ <br> No.
                </td>
                <td>เลขที่บิล <br> Invoice Number </td>
                <td>เลขที่ใบสั่งงาน <br> Order Number</td>
                <td>ชื่อคนไข้ <br> Patient Name</td>
                <td>ชื่อหมอ <br> Doctor</td>
                <td>ชื่องาน <br> Description</td>
                <td>ลงวันที่ <br> Invoice Date</td>
                <td>ยอดที่วางบิล <br> Amount</td>
            </tr>
            
            @foreach ($invoices ?? [] as $key => $invoice)
            @php
                $invoiceIds = explode(', ', $invoice['invoice_id'][1]);
            @endphp
            <tr>
                <td align="center">{{ ($key + 1) }}</td>
                <td>{{ $invoiceIds[1] ?? '' }}</td>
                <td>{{ $invoice['invoice_id'][0] ?? '' }}</td>
                <td>-</td>
                <td>-</td>
                <td>{{ $invoice['description'] ?? '' }}</td>
                <td>{{ date('d/m/Y', strtotime($invoice['date'] ?? '')) }}</td>
                <td align="right">{{ @number_format(removeComma($invoice['amount_total']), 2) }}</td>
            </tr>
            @endforeach
            @if (count($invoices) == 0)
                <tr>
                    <td colspan="8" align="center">ไม่สามารถแสดงข้อมูลได้ในขณะนี้</td>
                </tr>
            @endif
            <tr>
                <td align="center" colspan="6">
                    {{ $bill['amount_total_word'] }}
                </td>
                <td  align="right">จำนวนเงิน</td>
                <td align="right">{{ @number_format(removeComma($bill['sub_total']), 2) }}</td>
            </tr>
            <tr>
                <td colspan="7" align="right">ส่วนลด...</td>
                <td align="right">{{ @number_format(removeComma($bill['discount_amount']), 2) }}</td>
            </tr>
            <tr>
                <td colspan="7" align="right">ภาษีมูลค่าเพิ่ม</td>
                <td align="right"> {{ @number_format(removeComma($bill['tax_amount']), 2) }} </td>
            </tr>
            <tr>
                <td colspan="7" align="right">จำนวนเงินรวมทั้งสิ้น</td>
                <td align="right">{{ @number_format(removeComma($bill['amount_total']), 2) }}</td>
            </tr>
        </table>
    </div>
</body>
</html>