<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Setting;
use App\Member;
use App\MemberLogin;

use App\Payment;
use App\PaymentTransaction;

use DB;
use Excel;

class PaymentController extends Controller
{
    public $request;
    public $member;
    public $memberLogin;
    public $payment;
    public $paymentTransaction;

    public function __construct(
        Setting $setting,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Payment $payment,
        PaymentTransaction $paymentTransaction
    ) {
        $this->setting = $setting;
        $this->request = $request;
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->payment = $payment;
        $this->paymentTransaction = $paymentTransaction;
        $this->paymentUrl = env('PAYMENT_URL');

        $this->erpUrl = env('ERP_HOST', 'https://hexa-marketing.netforce.co.th/xmlrpc');
        $this->erpDB = env('ERP_DATABASE', 'hexa');
        $this->erpUsername = env('ERP_USERNAME', 5654);
        $this->erpPassword = env('ERP_PASSWORD', "1234");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];
        $conditions = [];

        $members = $this->member->getDataToSelect([['type', '!=', 'staff']]);
        $request = $this->request;

        $payments = $this->paymentTransaction
            //->where('full_step', 1)
            ->where(function($query) use ($request) {

                if ($request->has('member_id') && $request->input('member_id') != '')
                    $query->where('member_id', $request->input('member_id'));

                /*if ($request->has('approved_by') && $request->input('approved_by') != '')
                    $query->where('approved_by', $request->input('approved_by'));*/
                
                if ($request->has('type') && $request->input('type') != '')
                    $query->where('payment_type', $request->input('type'));

                if ($request->has('is_success') && $request->input('is_success') != '')
                    $query->where('is_success', $request->input('is_success'));
                
                if ($request->has('start_date') && $request->input('start_date') != '')
                    $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->input('start_date'))));
                
                if ($request->has('end_date') && $request->input('end_date') != '')
                    $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->input('end_date'))));

            })
            ->orderBy('id', 'desc');

        if ($request->has('type') && $request->input('type') == 'export') {

            $response['payments'] = $payments->get();

            Excel::create('payment-export-'. time(), function($excel) use ($response) {

                $excel->sheet('sheet1', function($sheet) use ($response) {
            
                    $sheet->loadView('admin.payment.excel', $response);
            
                });
            
            })->download('xls');
        }
        
        $response['payments'] = $payments->paginate(20);
        $response['members'] = $members;

        $types = DB::table('payment_transactions')->distinct()->get(['payment_type']);
        $response['types'] = $types;
        
        return view('admin.payment.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Payment $modelPayment)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = [];

        $payment = Payment::find($id);
        $response['payment'] = $payment;

        return view('payment.show', $response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $modelPayment, $id, $from)
    {
        $response = $request->input();
        
        $paymentTransaction = $this->paymentTransaction->find($id);
        $paymentTransaction->payment_return = json_encode($response);
        $paymentTransaction->ref1 = $response['transaction_ref'] ?? '';
        $paymentTransaction->is_success = ($response['payment_status'] == '000' || $response['payment_status'] == '001') ? 1 : 0;
        $paymentTransaction->save();
        
        try {
            
            if (!$paymentTransaction->is_success) {

                //$payment->delete();
                $message = [
                    'status' => false,
                    'message' => $response['channel_response_desc'],
                    'payment' => $paymentTransaction,
                    'paymentUrl' => $this->paymentUrl
                ];

                if ($from == 'app') {
            
                    return view('payment.response', $message);
                }
                
                return view('front.payment.response', $message);
            }
            
            $message = [
                'status' => $paymentTransaction->is_success,
                'message' => 'Your payment was ทำรายการสำเร็จ',
                'payment' => $paymentTransaction
            ];

            // update invoice to erp

            $this->updateErpBill($paymentTransaction);

            $accountings = $this->member->where('role', 2)->get();
            foreach ($accountings ?? [] as $accounting) {
                foreach ($accounting->logins ?? [] as $login) {
                    $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                        'title' => 'Payment Notification',
                        'message' => 'มีการชำระเงินใหม่'
                    ]);
                }
            }

            $member = $this->member->find($paymentTransaction->member_id);
            foreach ($member->logins ?? [] as $login) {
                $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                    'type' => 'payment_received',
                    'id' => $paymentTransaction->id,
                    'title' => 'Payment Notification',
                    'message' => 'การชำระเงินของลูกค้าได้รับการยืนยันแล้ว'
                ]);
            }

            if ($from == 'app') {
            
                return view('payment.response', $message);
            }
            
            return view('front.payment.response', $message);

        } catch (\Exception $e) {
            
            $message = [
                'status' => false,
                'message' => $e->getMessage(),
                'payment' => $paymentTransaction,
                'paymentUrl' => $this->paymentUrl
            ];
        }

        $message['paymentUrl'] = $this->paymentUrl;

        if ($from == 'app') {
            
            return view('payment.response', $message);
        }
        
        return view('front.payment.response', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $response = flash_message("ทำรายการสำเร็จ", true);
        
        $payment = $this->paymentTransaction->find($id);

        if (!$payment->delete()) {

            $response = flash_message('ทำรายการไม่สำเร็จ ลบข้อมูลการชำระเงินแล้ว');
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     * 
     */
    public function updateStatus($id)
    {
        $response = flash_message("ทำรายการสำเร็จ", true);

        $payment = $this->payment->find($id);
        $payment->confirmed = ($payment->confirmed) ? 0 : 1;
        $payment->approved_by = auth()->user()->id;
        $payment->save();

        $member = $this->member->find($payment->member_id);
        foreach ($member->logins ?? [] as $login) {
            $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                'title' => 'Payment Notification',
                'message' => 'การชำระเงินของลูกค้าได้รับการยืนยันแล้ว'
            ]);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     * 
     */
    /**
     * 
     * 
     */
    public function updateErpBill($payment)
    {
        $response = [];
        
        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "api.log";
        $method = "create_api_log";

        $bills = json_decode($payment->bill_content, true);
        $billIds = [];
        foreach ($bills as $bill) {

            $billIds[] = $bill['id'];
        }

        $params = [
            'app_id' => $payment->id,
            'amount' => $payment->total,
            'bill_ids' => $billIds,
            'meno' => $payment->ref1,
            'payment_method_id' => "4858",
        ];

        $condArgs = [$params];

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
        
        return;
    }

    
}
