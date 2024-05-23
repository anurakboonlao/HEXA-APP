<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>สถานะการชำระเงิน</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <script src="{{ url('/') }}/assets/AdminLTE-2.4.2/bower_components/jquery/dist/jquery.min.js"></script>
    <style>
        .btn {
            border-radius: 0px;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#payment-success').on('click', function(e) {
                
                window.ReactNativeWebView.postMessage("payment_success-{{ $payment->id }}", "*");
            })

            $('#payment-error').on('click', function(e) {
                
                window.ReactNativeWebView.postMessage("payment_error-{{ $payment->id }}", "*");
            })
        });
    </script>
</head>
@php
    $bills = json_decode($payment->bill_content, true);
    $paymentRef = json_decode($payment->payment_return, true)
@endphp
<body>
    <div class="container">
        @if ($status)
        <div class="row">
            <div class="col-xs-12">
                <h4>Payment receipt</h4>
                <p>Payment referrence</p>
                <p>{{ $payment->id }}</p>
                <p>Payment date-time</p>
                <p>{{ date('d/m/Y H:s', strtotime($payment->updated_at)) }}</p>
                <br>
                <table class="table" width="100%">
                    @foreach ($bills as $bill)
                    <tr>
                        <td>{{ $bill['number'] }}</td>
                        <td align="right">{{ @number_format(removeComma($bill['amount_total']), 2) }} THB</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>
                            Total
                        </td>
                        <td align="right">
                            {{ @number_format(removeComma($payment['total']), 2) }} THB
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="2">
                            <!-- <br>
                            <a class="btn btn-primary" id="payment-success" href="#">< กลับไปยัง Hexa Ceram Application</a> -->
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @else
        <br><br>
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="alert alert-danger">
                    <h4>{{ $paymentRef['channel_response_desc'] }}</h4>
                </div>
                <br>
                <a href="#" id="payment-error" class="">
                    หากต้องการชำระเงินอีกครั้งกรุณากดไอคอน "<" ตรงมุมบนซ้ายของแอป
                </a>
            </div>
        </div>
        @endif
    </div>
</body>
</html>