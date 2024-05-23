<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Setting;
use App\Member;
use App\MemberLogin;

use App\Payment;
use App\PaymentTransaction;
use App\PaymentTransactionFile;
use DB;


class TransferController extends Controller
{

    public function __construct() {
        $this->erpUrl = env('ERP_HOST', 'https://hexa-marketing.netforce.co.th/xmlrpc');
        $this->erpDB = env('ERP_DATABASE', 'hexa');
        $this->erpUsername = env('ERP_USERNAME', 5654);
        $this->erpPassword = env('ERP_PASSWORD', "1234");
    }

    
    public function updateApprove(){
        $payment_transaction_id = request()->payment_transaction_id;
        $payment_type = request()->payment_type;

        $payment = DB::table('payment_transactions')
                        ->where('id', $payment_transaction_id)
                        ->first();

        // Log::info(__FILE__ ." | Line: " . __LINE__ . " payment: " . print_r($payment, true));

        $result = $this->updateErpBill($payment); 

        // Log::info(__FILE__ ." | Line: " . __LINE__ . " result: " . print_r($result,true));

        if($result == 200){
            DB::table('payment_transactions')
            ->where('id', $payment_transaction_id)
            ->update(['is_success'=> 1]);
        }
        

        return back()
            ->with('success','You have successfully update.');
    }

    public function showTransferFile(){
        $payment_transaction_id = request()->payment_transaction_id;

        $response = [];
        $response['payment_transaction_files'] = array();
        $payment_transaction_files = PaymentTransactionFile::where('payment_transaction_id', $payment_transaction_id)->get();
        $response['payment_transaction_files'] = $payment_transaction_files;

        return view('admin.payment.file', $response);

        // return $payment_transaction_files;
    }
    
    public function updateErpBill($payment)
    {
        $response = [];
        
        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "api.log";
        $method = "create_api_log";

        $bills = json_decode($payment->bill_content, true);

        // Log::info(__FILE__ ." | Line: " . __LINE__ . " bills: " . print_r($bills, true));

        $billIds = [];
        $count = 0;
        foreach ($bills as $bill) {
            // Log::info(__FILE__ ." | Line: " . __LINE__ . " bill: " . print_r($bill, true));
            $billIds[] = $bill['id'];
        }

        // Log::info(__FILE__ ." | Line: " . __LINE__ . " billIds: " . print_r($billIds, true));

        if($payment->payment_type == "transfer"){
            $params = [
                'app_id' => $payment->id,
                'amount' => $payment->total,
                'bill_ids' => $billIds,
                'meno' => $payment->ref1,
                'payment_method_id' => "4007",
            ];
        }
        elseif($payment->payment_type == "qr credit"){
            $params = [
                'app_id' => $payment->id,
                'amount' => $payment->total,
                'bill_ids' => $billIds,
                'meno' => $payment->ref1,
                'payment_method_id' => "4868",
                'memo' => "qr",
            ];
        }
        else{
            $params = [
                'app_id' => $payment->id,
                'amount' => $payment->total,
                'bill_ids' => $billIds,
                'meno' => $payment->ref1,
                'payment_method_id' => "4858",
            ];
        }
        // Log::info(__FILE__ ." | Line: " . __LINE__ . " params: " . print_r($params, true));

        $condArgs = [$params];

        Log::info(__FILE__ ." | Line: " . __LINE__ . " condArgs: " . print_r($condArgs, true));

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
        Log::info(__FILE__ ." | Line: " . __LINE__ . " response: " . print_r($response, true));
        return $response;
    }



}
