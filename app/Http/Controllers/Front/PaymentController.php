<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Member;
use App\MemberLogin;
use App\Payment;

use App\Zone;
use App\ZoneMember;

use DB;

use PDF;

use Validator;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use Auth;
use Session;

class PaymentController extends Controller
{

    public $request;
    public $member;
    public $memberLogin;
    public $remoteMysql;
    public $payment;

    public $zone;
    public $zoneMember;

    public $erpUrl;
    public $erpDB;
    public $erpUsername;
    public $erpPassword;

    public function __construct(
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Payment $payment,
        Zone $zone,
        ZoneMember $zoneMember
    ) {
        $this->request = $request;
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->remoteMysql = DB::connection('mysql2');
        $this->payment = $payment;

        $this->zone = $zone;
        $this->zoneMember = $zoneMember;

        $this->erpUrl = env('ERP_HOST', 'https://hexa-marketing.netforce.co.th/xmlrpc');
        $this->erpDB = env('ERP_DATABASE', 'hexa');
        $this->erpUsername = env('ERP_USERNAME', 5654);
        $this->erpPassword = env('ERP_PASSWORD', "1234");
    }
    
    public function getMemberInvoices($id)
    {
        $response = flash_message('คุณยังไม่ได้ทำการ Invite Code ยืนยันตัวตน');

        $member = $this->member->find($id);

        if (!$member->customer_verify_key) {
            
            return redirect()->back()->with('message', $response);
        }

        $jsonData = $this->request->all();

        $type = $jsonData['type'] ?? 'all';
        $ids = $jsonData['ids'] ?? [];
        
        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "account.bill";
        $method = "search_read";
        
        $searchConditions = [
            ['contact_id', '=', $member->customer_id],
            ['state', '=', 'confirmed']
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
        
        $response = flash_message('success', true);

        $billData = [];
        foreach ($bills ?? [] as $bill) {
            if ($this->payment->where('bill_ids', 'like', '%'. $bill['id'] .'%')->where('confirmed', 1)->count() == 0) {
                $billData[] = $bill;
            }
        }

        (!empty($bills) ? $response['data'] = map_bill_issue_response($billData, $type, $ids) :  $response['data'] = $billData);

        //debug response bill invoices all

        return view('front.payment.index', $response);
    }
}
