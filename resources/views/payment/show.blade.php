<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ชำระค่าสินค้าและบริการ</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets/AdminLTE-2.4.2/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/AdminLTE-2.4.2/bower_components/font-awesome/css/font-awesome.min.css">
</head>
<body>
    <div class="container">
        <br>
        @php
            $bill = json_decode($payment->bill_content, true);
            $order_id = time();
            $bill_number = '';
            foreach ($bill['bills'] as $key => $bill) {
                $bill_number .= ($key > 0) ? ',' : '';
                $bill_number .= $bill['number'];
            }

            $merchant_id = env('MERCHANT_ID', "JT04");			//Get MerchantID when opening account with 2C2P
            $secret_key = env('SECRET_KEY', "QnmrnH6QE23N");	//Get SecretKey from 2C2P PGW Dashboard
            
            //Transaction information
            $payment_description  = 'ชำระบิล ' . $bill_number;
            $currency = "764";
            $amount  = str_pad($payment->amount_total, 10, 0, STR_PAD_LEFT) . '00';

            $customer_email = $payment->member->email;
            
            //Payment Options
	        $payment_option = "C";	//Customer Payment Options
            
            //Request information
            $version = "8.5";	
            $payment_url = env('PAYMENT_URL', "https://demo2.2c2p.com/2C2PFrontEnd/RedirectV3/payment");
            $return_url = env('RETURN_URL', "http://localhost:8072/www/hexa/public/payment/") . $payment->id;
            
            //Construct signature string
            $params = $version . $merchant_id . $payment_description . $order_id . $currency . $amount . $customer_email . $return_url . $payment_option;
            $hash_value = hash_hmac('sha256', $params, $secret_key, false);
        @endphp
        <div class="row">
            <div class="col-xs-12 text-center">
                <h4>ชำระค่าสินค้าและบริการ</h4>
                <p>เลขที่บิล 
                    {{ $bill_number }}
                </p>
                <h4>จำนวนเงินที่ต้องชำระ {{ @number_format($payment->amount_total, 2) }} บาท</h4>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-4">
                <img src="{{ url('/') }}/images/2C2P-Online-Payment.png" alt="" width="100%">
            </div>
            <div class="col-xs-8">
                <p>Easy Bills by 2C2P (Visa)</p>
                <p>วงเงินที่รับชำระ 1.00 - 100,000.00 บาท</p>
                <p>ค่าธรรมเนียม 0.00 บาท</p>
                <p>ให้บริการ 00.05 - 21.45 น.</p>
            </div>
        </div>
        <hr>
        <div class="row">
            {{ Form::open(['url' => $payment_url]) }}
            <input type="hidden" name="version" value="{{ $version }}">
            <input type="hidden" name="merchant_id" value="{{ $merchant_id }}">
            <input type="hidden" name="currency" value="{{ $currency }}">
            <input type="hidden" name="customer_email" value="{{ $customer_email }}">
            <input type="hidden" name="result_url_1" value="{{ $return_url }}">
            <input type="hidden" name="payment_option" value="{{ $payment_option }}"/>
            <input type="hidden" name="hash_value" value="{{ $hash_value }}">
            <input type="hidden" name="payment_description" value="{{ $payment_description }}">
            <input type="hidden" name="order_id" value="{{ $order_id }}">
            <input type="hidden" name="amount" value="{{ $amount }}">
            <div class="col-xs-12 text-center">
                <p>
                    <button class="btn btn-success btn-lg" type="submit">
                        <i class="fa fa-money"></i>
                        ชำระเงิน
                    </button>
                </p>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</body>
</html>