<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Setting;
use App\Member;
use App\MemberLogin;
use App\Cart;
use App\Product;
use App\ProductDiscount;

use App\Order;
use App\Payment;
use App\PaymentTransaction;

use DB;

use Validator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class PaymentController extends Controller
{
    public $request;
    public $setting;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $cart;
    public $product;
    public $productDiscount;
    public $order;
    public $payment;
    public $httpRequest;
    public $paymentTransaction;

    public function __construct(
        Setting $setting,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Cart $cart,
        Product $product,
        ProductDiscount $productDiscount,
        Order $order,
        Payment $payment,
        PaymentTransaction $paymentTransaction
    ) {
        $this->setting = $setting;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->cart = $cart;
        $this->product = $product;
        $this->productDiscount = $productDiscount;
        $this->order = $order;
        $this->payment = $payment;
        $this->httpRequest = new Client();
        $this->paymentTransaction = $paymentTransaction;
        $this->paymentUrl = env('PAYMENT_URL');
    }

    /**
     * 
     * 
     */
    public function getPaymentHistory()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $payments = $this->paymentTransaction
                ->where([
                    ['is_success', 1],
                    ['member_id', $memberLogin->member_id]
                ])
                ->whereYear('created_at', date('Y'))
                ->orderBy('updated_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();
            
            $response = flash_message('success', true);
            $response['data'] = map_payment_member_response($payments);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getPaymentHome($status = 'incoming')
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            
            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $payments = $this->paymentTransaction
                ->where([
                    ['is_success', 1]
                ])
                ->orderBy('id', 'desc')
                ->get();
        
            $response = flash_message('success', true);
            $response['data'] = map_payment_accounting_response($payments);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getPaymentDetail()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $param = $this->request->all();
            
            $validator = Validator::make($param, [
                'payment_id' => 'required',
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $payment = $this->paymentTransaction->find($param['payment_id']);
            $bills = json_decode($payment->bill_content, true);

            $paymentRef = json_decode($payment->payment_return, true);

            foreach ($bills ?? [] as $key => $invoice) {
                $invoice['amount_total'] = @number_format(removeComma($invoice['amount_total']), 2);
                $bills['invoices'][$key] = $invoice;
            }

            $response = flash_message('success', true);
            $response['data'] = [
                'id' => $payment->id,
                'date' => set_date_format($payment->created_at),
                'status' => $payment->is_success,
                'invoices' => $bills['invoices'],
                'amount_total' => @number_format(removeComma($payment->total), 2),
                'payment_type' => [
                    'type' => "Online Payment.",
                    'reference' => $payment->ref1
                ]
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function confirm()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $jsonData = $this->request->json()->all();
            
            $validator = Validator::make($jsonData, [
                'payment_id' => 'required',
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $payment = $this->payment->find($jsonData['payment_id']);
            $payment->is_success = 1;
            $payment->approved_by = $memberLogin->member_id;
            $payment->save();
            
            $response = flash_message('Success', true);

            $members = $this->member->where('id', $payment->member_id)->get();
            foreach ($members ?? [] as $member) {
                foreach ($member->logins ?? [] as $login) {
                    $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                        'title' => 'Payment Notification',
                        'message' => 'การชำระเงินของลูกค้าได้รับการยืนยันแล้ว'
                    ]);
                }
            }

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     * 
     */
    public function transaction()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response);
            }

            $jsonData = $this->request->json()->all();
            
            $validator = Validator::make($jsonData, [
                'invoices' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $total = 0;
            $billText = "";
            foreach ($jsonData['invoices'] as $bill) {

                $checkBill = $this->paymentTransaction
                    ->where('bill_content', 'like', '%'. $bill['id'] .'%')
                    ->where('is_success', 1)
                    ->first();

                /*if ($checkBill) {

                    $response['message'] = $bill['number'] . " มีการชำระเงินไปก่อนหน้านี้แล้ว";
                    return response()->json($response);
                }*/

                $total += $bill['amount_total_origin'];
                $billText .= "_" . $bill['number'];
            }

            //Merchant's account information
            $merchantId = env('MERCHANT_ID');//Get MerchantID when opening account with 2C2P
            $secretKey = env('SECRET_KEY');//Get SecretKey from 2C2P PGW Dashboard

            //Transaction information
            $paymentDescription  = 'HexaPaymentBalanceDue' . $billText;
            $orderId  = time();
            $currency = "764";
            $amount = intval(str_replace(',', '', $total));
            $amount = str_pad($total, 10, 0, STR_PAD_LEFT) . '00';

            //Payment Options
            $paymentOption = "CC,Q";

            //Request information
            $version = "8.5";	
            $paymentUrl = $this->paymentUrl;
            
            $this->paymentTransaction->member_id = $memberLogin->member_id;
            $this->paymentTransaction->bill_content = json_encode($jsonData['invoices']);
            $this->paymentTransaction->payment_type = "online";
            $this->paymentTransaction->total = $total;
            $this->paymentTransaction->is_success = 0;
            $this->paymentTransaction->save();
            
            $resultUrl1 = url("/") . "/payment/" . $this->paymentTransaction->id .'/app';

            $request3ds = "";
            $enableStoreCard = "";
            $use_storedcard_only = "";
            $customer_email = "";
            $pay_category_id = "";
            $promotion = "";
            $user_defined_1 = "";
            $user_defined_2 = "";
            $user_defined_3 = "";
            $user_defined_4 = "";
            $user_defined_5 = "";
            $result_url_2 = "";
            $stored_card_unique_id = "";
            $recurring = "";
            $order_prefix = "";
            $recurring_amount = "";
            $allow_accumulate = "";
            $max_accumulate_amount = "";
            $recurring_interval = "";
            $recurring_count = "";
            $charge_next_date = "";
            $charge_on_date = "";
            $ipp_interest_type = "";
            $payment_expiry = "";
            $default_lang = "";
            $statement_descriptor = "";
            $tokenize_without_authorization = "";
            $product = "";
            $ipp_period_filter = "";
            $sub_merchant_list = "";
            $qr_type = "";
            $custom_route_id = "";
            $airline_transaction = "";
            $airline_passenger_list = "";
            $address_list = "";
            $invoice_no = $this->paymentTransaction->id;

            //Construct signature string
            $params = $version . $merchantId . $paymentDescription . $orderId . $invoice_no . 
            $currency . $amount . $customer_email . $pay_category_id . $promotion . $user_defined_1 . 
            $user_defined_2 . $user_defined_3 . $user_defined_4 . $user_defined_5 . $resultUrl1 . 
            $result_url_2 . $enableStoreCard . $stored_card_unique_id . $request3ds . $recurring . 
            $order_prefix . $recurring_amount . $allow_accumulate . $max_accumulate_amount . 
            $recurring_interval . $recurring_count . $charge_next_date. $charge_on_date . $paymentOption . 
            $ipp_interest_type . $payment_expiry . $default_lang . $statement_descriptor . $use_storedcard_only .
            $tokenize_without_authorization . $product . $ipp_period_filter . $sub_merchant_list . $qr_type .
            $custom_route_id . $airline_transaction . $airline_passenger_list . $address_list;

            $hashValue = hash_hmac('sha256', $params, $secretKey, false);//Compute hash value

            $params = [
                'version' => $version,
                'merchant_id' => $merchantId,
                'currency' => $currency,
                'result_url_1' => $resultUrl1,
                'enable_store_card' => $enableStoreCard,
                'request_3ds' => $request3ds,
                'payment_option' => $paymentOption,
                'hash_value' => $hashValue,
                'payment_description' => $paymentDescription,
                'order_id' => $orderId,
                'amount' => $amount,
                'url_to_submit' => $paymentUrl,
                'payment_transaction_id' => $invoice_no
            ];

            $response = flash_message('Success', true);
            $response['data'] = [
                'payment_url' => urldecode(url('/') . '/payment_redirect?' . http_build_query($params)),
                'payment_params' => [
                    'version' => $version,
                    'merchant_id' => $merchantId,
                    'currency' => $currency,
                    'result_url_1' => $resultUrl1,
                    'enable_store_card' => $enableStoreCard,
                    'request_3ds' => $request3ds,
                    'payment_option' => $paymentOption,
                    'hash_value' => $hashValue,
                    'payment_description' => $paymentDescription,
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'url_to_submit' => $paymentUrl
                ]
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function paymentRedirect()
    {
        $params = $this->request->all();

        return view('payment.redirect', $params);
    }
}