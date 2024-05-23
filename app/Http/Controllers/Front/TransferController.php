<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Member;
use App\MemberLogin;
use App\Payment;
use App\PaymentTransaction;
use App\DoctorContact;
use App\PaymentTransactionFile;
use App\Zone;
use App\ZoneMember;
use App\Setting;

use DB;

use PDF;

use Validator;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use Auth;
use Session;

class TransferController extends Controller
{
    public $request;
    public $member;
    public $memberLogin;
    public $remoteMysql;
    public $payment;
    public $paymentTransaction;
    public $doctorContact;
    public $payment_transaction_id;

    public $zone;
    public $zoneMember;
    public $setting;

    public $erpUrl;
    public $erpDB;
    public $erpUsername;
    public $erpPassword;

    public function __construct(
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Payment $payment,
        DoctorContact $doctorContact,
        Zone $zone,
        ZoneMember $zoneMember,
        Setting $setting,
        PaymentTransaction $paymentTransaction
    ) {
        $this->request = $request;
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->remoteMysql = DB::connection('mysql2');
        $this->payment = $payment;
        $this->doctorContact = $doctorContact;

        $this->zone = $zone;
        $this->zoneMember = $zoneMember;
        $this->setting = $setting;
        $this->paymentTransaction = $paymentTransaction;

        $this->erpUrl = env('ERP_HOST', 'https://hexa-marketing.netforce.co.th/xmlrpc');
        $this->erpDB = env('ERP_DATABASE', 'hexa');
        $this->erpUsername = env('ERP_USERNAME', 5654);
        $this->erpPassword = env('ERP_PASSWORD', "1234");

        $this->paymentUrl = env('PAYMENT_URL');
    }

    /**
     * 
     * 
     * 
     */
    public function home()
    {
        $response = [];

        $payment_transaction_id = request()->payment_transaction_id;
        $this->payment_transaction_id = $payment_transaction_id;

        // DB::table('payment_transactions')
        //     ->where('id', $payment_transaction_id)
        //     ->update(['payment_type' => 'transfer']);
            
        $payment_transaction = DB::table('payment_transactions')
            ->where('id', $payment_transaction_id)
            ->get();
        // dd($payment_transaction);
        $member = $this->member->find($this->request->member->id);

        $memberIds = $this->member
            ->where('customer_verify_key', $member->customer_verify_key)
            ->get()
            ->pluck('id');

        $response['bill'] = $this->getMemberInvoices($member);
        $response['payment_transaction_id'] = $payment_transaction_id;
        $response['payments'] = $this->paymentTransaction
            ->whereIn('member_id', $memberIds)
            ->where('is_success', 1)
            ->orderBy('id', 'desc')
            ->paginate(16);

        $response['payment_transaction'] = $payment_transaction;
        $response['payment_type'] = $payment_transaction[0]->payment_type;
        $response['payment_transaction_files'] = array();
        $payment_transaction_files = PaymentTransactionFile::where('payment_transaction_id', request()->payment_transaction_id)->get();
        $response['payment_transaction_files'] = $payment_transaction_files;


        return view('front.transfer.home', $response);
    }

    public function fileUploadPost() {
        request()->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);
  
        $payment_transaction_id = request()->payment_transaction_id;
        $fileName = time(). '_' . request()->file->getClientOriginalName() .'.' .request()->file->extension();  
   
        // $imageName = time().'.'.request()->file->getClientOriginalExtension();

        request()->file->move(public_path('uploads/payments/'), $fileName);

        $flight = PaymentTransactionFile::create([
            'payment_transaction_id' => (int)$payment_transaction_id,
            'file_path' => $fileName
        ]);


        return back()
            ->with('success','You have successfully upload image.')
            ->with('file',$fileName);
    
    }

    public function fileDelete() {
        $payment_transaction_file_id = request()->payment_transaction_file_id;

        DB::table('payment_transaction_files')
            ->where('id', $payment_transaction_file_id)
            ->delete();
        
        return back()
            ->with('success','You have successfully delete image.');
    }

    public function updateConfirm(){
        $response = flash_message();

        $payment_transaction_id = request()->payment_transaction_id;
        // dd($payment_transaction_id);
        $payment_type = request()->payment_type;
        // dd(request()->payment_type);

        if($payment_type == ''){
            $response = flash_message("กรุณาเลือกประเภทการชำระเงิน", false);
            return redirect()->back()->with('message', $response);
        }

        $result = DB::table('payment_transactions')
            ->where('id', $payment_transaction_id)
            ->update(['payment_type' => $payment_type]);
        // dd($result);  

        $result = DB::table('payment_transactions')
            ->where('id', $payment_transaction_id)
            ->update(['is_transfer_confirmed'=> 1]);
        // dd($result);    
          
        if ($result) {
            $response = flash_message("ยืนยันการโอนสำเร็จแล้ว", true);
        }
            
        return redirect()->back()->with('message', $response);
    }

    
    /**
     * 
     * 
     */
    public function getMemberInvoices($member)
    {
        $type = 'all';
        $ids = [];
        
        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "account.bill";
        $method = "search_read";
        
        $searchConditions = [
            ['contact_id', '=', $member->customer_id],
            ['state', '=', 'confirmed'],
            //['invoiced', '=', true]
        ];

        $condArgs = [
            $searchConditions,
            []
        ];
        
        $opt = [];
        
        $client = new \App\Services\XmlrpcClient($this->erpUrl);
        $bills = $client->call(
            'execute', 
            $tableName, 
            $method,
            $condArgs, 
            $opt, 
            $db,
            $username, 
            ""
        );

        $billData = [];
        foreach ($bills ?? [] as $bill) {
            if ($this->payment->where('bill_ids', 'like', '%'. $bill['id'] .'%')->where('confirmed', 1)->count() == 0) {
                $billData[] = $bill;
            }
        }

        return map_bill_issue_response($billData, $type, $ids);
    }

    /**
     * 
     * 
     */
    public function getBillDetail($billId)
    {   
        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "account.bill";
        $method = "search_read";
        
        $searchConditions = [
            ['id', '=', $billId],
        ];
        
        $condArgs = [
            $searchConditions,
            []
        ];
        
        $opt = [];
        
        $client = new \App\Services\XmlrpcClient($this->erpUrl);
        $bill = $client->call(
            'execute', 
            $tableName, 
            $method, 
            $condArgs, 
            $opt, 
            $db, 
            $username, 
            ""
        );

        if (count($bill) == 0) {

            return response()->json($response);
        }

        $bill = array_first($bill);

        $invoices = $client->call(
            'execute', 
            'account.bill.line', 
            $method, 
            [
                ['bill_issue_id', '=', $bill['id']]
                ,
                []
            ], 
            $opt, 
            $db, 
            $username, 
            ""
        );

        $host = "http://hexa-api.netforce.co.th";
        $url = $client->call(
            'execute', 
            'account.bill', 
            'print_api_bill_paymnet',
            [[$bill['id']]], 
            $opt,
            $db,
            $username,
            ""
        );

        $url = is_array($url) ? "/" : $url;
        $contents = file_get_contents($host . $url);
        $fileName = date('Ymd') . $bill['id'] . '.pdf';

        $fullPath = public_path() . '/bills/' . $fileName;
        
        file_put_contents($fullPath, $contents);

        $response['data']['bill'] = $bill;
        $response['data']['invoices'] = $invoices;

        $response['data']['pdf_url'] = url('/') . '/bills/' . $fileName;

        return $response;
    }

    /**
     * 
     * 
     */
    public function findContactErp($code, $field = 'code')
    {
        $response = [];
        
        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "contact";
        $method = "search_read";

        $condArgs = [
            [
                [$field, '=', $code], 
                ['active', '=', true],
                ['place','=','domestic']
            ], 
            []
        ];

        $opt = [];

        $client = new \App\Services\XmlrpcClient($this->erpUrl);
        $response = $client->call(
            'execute', 
            $tableName, 
            $method, 
            $condArgs, 
            $opt, 
            $db, 
            $username, 
            ""
        );

        return $response[0] ?? [];
    }

    /**
     * 
     * 
     */
    public function findDoctorErp($code)
    {
        $response = [];
        
        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "doctor";
        $method = "search_read";

        $condArgs = [
            [
                ['code', '=', $code],
                ['active', '=', true]
            ], 
            ['code', 'name', 'clinic', 'contact_ids', 'balance_point', 'claim_point', 'paid_amount', 'amount_point', 'total_point']
        ];

        $opt = [];
        $client = new \App\Services\XmlrpcClient($this->erpUrl);
        $response = $client->call(
            'execute',
            $tableName, 
            $method, 
            $condArgs,
            $opt, 
            $db, 
            $username, 
            ""
        );

        //return $response;
        return $response[0] ?? [];
    }

    /**
     * 
     * 
     */
    public function getBillErp($billId)
    {   
        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "account.bill";
        $method = "search_read";
        
        $searchConditions = [
            ['id', '=', $billId],
        ];
        
        $condArgs = [
            $searchConditions,
            []
        ];
        
        $opt = [];
        
        $client = new \App\Services\XmlrpcClient($this->erpUrl);
        $bill = $client->call(
            'execute', 
            $tableName, 
            $method, 
            $condArgs, 
            $opt, 
            $db, 
            $username, 
            ""
        );

        if (count($bill) == 0) {

            return [];
        }

        $bill = array_first($bill);

        $invoices = $client->call(
            'execute', 
            'account.bill.line', 
            $method, 
            [
                ['bill_issue_id', '=', $bill['id']]
                ,
                []
            ], 
            $opt, 
            $db, 
            $username, 
            ""
        );

        $url = $client->call(
            'execute', 
            'account.bill', 
            'print_api_bill_paymnet',
            [[$bill['id']]], 
            $opt,
            $db,
            $username,
            ""
        );

        $response['bill'] = $bill;

        return $bill;
    }

    /**
     * 
     * 
     * 
     */
    public function getPreviewBills()
    {
        $params = $this->request->all();

        $bills = [];
        $totalAmount = 0;
        $billText = "";

        $params['bills'] = empty($params['bills']) ? [] : $params['bills'];

        foreach ($params['bills'] as $billId) {

            $bill = $this->getBillErp($billId);
            $bills[] = $bill;

            $totalAmount += $bill['total_amount'];
            $billText .= "_" . $bill['number'];
        }

        $response['bills'] = $bills;
        $response['total_amount'] = $totalAmount;

        //Merchant's account information
        $merchantId = env('MERCHANT_ID');//Get MerchantID when opening account with 2C2P
        $secretKey = env('SECRET_KEY');//Get SecretKey from 2C2P PGW Dashboard

        //Transaction information
        $paymentDescription  = 'HexaPaymentBalanceDue' . $billText;
        $orderId  = time();
        $currency = "764";
        $amount = intval(str_replace(',', '', round($totalAmount)));
        $amount = str_pad(round($totalAmount), 10, 0, STR_PAD_LEFT) . '00';

        //Payment Options
        $paymentOption = "CC,Q";

        //Request information
        $version = "8.5";	
        $paymentUrl = $this->paymentUrl;
        
        $this->paymentTransaction->member_id = $this->request->member->id;
        $this->paymentTransaction->bill_content = json_encode($bills);
        $this->paymentTransaction->payment_type = "online";
        $this->paymentTransaction->total = round($totalAmount);
        $this->paymentTransaction->is_success = 0;
        $this->paymentTransaction->save();
        
        $resultUrl1 = url("/") . "/payment/" . $this->paymentTransaction->id . '/web';

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

        return view('front.bill.preview', $response);
    }
}
