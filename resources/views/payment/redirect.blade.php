<?php
error_reporting(E_ALL & ~E_NOTICE);

//Merchant's account information
$merchant_id = env("MERCHANT_ID", "JT04");		//Get MerchantID when opening account with 2C2P
$secret_key = env("SECRET_KEY", "QnmrnH6QE23N");	//Get SecretKey from 2C2P PGW Dashboard

//Transaction information
$payment_description  = $payment_description;
$order_id  = time();
$currency = $currency;
$amount  = $amount;

//Payment Options
$payment_option = $payment_option;	//Customer Payment Options

//Request information
$version = "8.5";	
$payment_url = env("PAYMENT_URL", "https://demo2.2c2p.com/2C2PFrontEnd/RedirectV3/payment");
$result_url_1 = $result_url_1;

//Construct signature string
$params = $version . $merchant_id . $payment_description . $order_id . $invoice_no . 
$currency . $amount . $customer_email . $pay_category_id . $promotion . $user_defined_1 . 
$user_defined_2 . $user_defined_3 . $user_defined_4 . $user_defined_5 . $result_url_1 . 
$result_url_2 . $enable_store_card . $stored_card_unique_id . $request_3ds . $recurring . 
$order_prefix . $recurring_amount . $allow_accumulate . $max_accumulate_amount . 
$recurring_interval . $recurring_count . $charge_next_date. $charge_on_date . $payment_option . 
$ipp_interest_type . $payment_expiry . $default_lang . $statement_descriptor . $use_storedcard_only .
$tokenize_without_authorization . $product . $ipp_period_filter . $sub_merchant_list . $qr_type .
$custom_route_id . $airline_transaction . $airline_passenger_list . $address_list;

$hash_value = hash_hmac('sha256',$params, $secret_key,false);	//Compute hash value

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <title>Redirect...</title>
</head>
<body>
	<form id="myform" method="post" action="{{ $payment_url }}">
        <input type="hidden" name="version" value="{{ $version }}">
        <input type="hidden" name="merchant_id" value="{{ $merchant_id }}">
        <input type="hidden" name="currency" value="{{ $currency }}">
        <input type="hidden" name="result_url_1" value="{{ $result_url_1 }}">
        <input type="hidden" name="payment_option" value="{{ $payment_option }}"/>
        <input type="hidden" name="hash_value" value="{{ $hash_value }}">
        <input type="hidden" name="payment_description" value="{{ $payment_description }}">
        <input type="hidden" name="order_id" value="{{ $order_id }}">
        <input type="hidden" name="amount" value="{{ $amount }}">
        <div class="col-xs-12 text-center">
            <p>
                <button class="btn btn-success btn-lg" type="submit">
                    <i class="fa fa-money"></i>
                    ชำระเงิน...
                </button>
            </p>
        </div>
	</form>

	<script type="text/javascript">
		document.forms.myform.submit();
	</script>
</body>
</html>