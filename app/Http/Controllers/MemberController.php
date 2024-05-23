<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Member;
use App\Zone;
use App\ZoneMember;

use Excel;

use DB;


class MemberController extends Controller
{
    public $product;
    public $member;
    public $zone;
    public $zoneMember;

    public function __construct(
        Member $member,
        Product $product,
        Zone $zone,
        ZoneMember $zoneMember
    ) {
        $this->product = $product;
        $this->member = $member;
        $this->zone = $zone;
        $this->zoneMember = $zoneMember;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];

        $response['members'] = $this->member
            ->where('type', 'staff')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.member.index', $response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customers(Request $request)
    {
        $response = [];

        $customers = $this->member
            ->where('role', 1)
            ->where(function($query) use ($request) {

                if ($request->has('customer_type') && $request->input('customer_type') != '') {

                    $query->where('type', $request->input('customer_type'));
                }

                if ($request->has('verify') && $request->input('verify') != '') {

                    if ($request->input('verify') == 'false') {

                        $query->whereNull('customer_verify_key');

                    }else {

                        $query->whereNotNull('customer_verify_key');
                    }
                }

                if ($request->has('key') && $request->input('key') != '') {
                    $query->orWhere('customer_verify_key', 'like', '%'. $request->input('key') .'%');
                    $query->orWhere('name', 'like', '%'. $request->input('key') .'%');
                    $query->orWhere('email', 'like', '%'. $request->input('key') .'%');
                    $query->orWhere('phone', 'like', '%'. $request->input('key') .'%');
                    $query->orWhere('username', 'like', '%'. $request->input('key') .'%');
                }
            })
            ->orderBy('id', 'desc');

        if ($request->has('type') && $request->input('type') == 'export') {

            $response['customers'] = $customers->get();

            Excel::create('customers-export-'. time(), function($excel) use ($response) {

                $excel->sheet('sheet1', function($sheet) use ($response) {
            
                    $sheet->loadView('admin.customer.excel', $response);
            
                });
            
            })->download('xls');
        }

        $response['customers'] = $customers->paginate(30);

        return view('admin.customer.index', $response);
    }

    /**
     * Edit Username & Password Customer
     */
    public function editCustomer($customerId)
    {
        $customer = $this->member->find($customerId);

        $response['customer'] = $customer;

        return view('admin.customer.update', $response);
    }

    /**
     * Update Username & Password Customer
     */
    public function updateCustomerAccount(Request $request, $id)
    {
        $response = flash_message();

        $member = $this->member->find($id);
        $member->username = $request->input('username');
        if ($request->input('password')) {

            $member->password = md5($request->input('password'));
        }
        $member->name = $request->input('name');
        $member->address = $request->input('address');
        $member->phone = $request->input('phone');        

        if ($member->save()) {

            $response = flash_message("แก้ไข Username และ Password สำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = [];
        $response['zones'] = $this->zone->getDataToSelect();

        return view('admin.member.create', $response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Member $modelMember)
    {
        $response = flash_message();

        $request->validate([
            'username' => 'required|unique:members',            
            'email' => 'required|email|unique:members',
            'name' => 'required',
            'password' => 'required',
        ]);

        DB::beginTransaction();

        // save to members table
        $modelMember->username = $request->input('username');
        $modelMember->password = md5($request->input('password'));
        $modelMember->name = $request->input('name');
        $modelMember->email = $request->input('email');
        $modelMember->phone = $request->input('phone');
        $modelMember->line_id = $request->input('line_id');
        $modelMember->line_secret_code = uniqid();
        $modelMember->role = $request->input('role');
        
        $modelMember->type = 'staff';
        //$this->member->zone_id = $request->input('zone_id');

        if (!$modelMember->save()) {
 
            $response = flash_message('ไม่สามารถทำรายการได้');
            return redirect()->back()->with('message', $response);
        }

        // save to zone_members table
        $zoneMemberMultiple = [];
        if ($request->has('zone_id')) {
            foreach ($request->input('zone_id') ?? [] as $key => $value) {
                $zoneMemberMultiple[] = ['zone_id' => $value,
                    'member_id' => $modelMember->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s') 
                ];
            }
        }

        $modelMember->save();
        $this->zoneMember->insert($zoneMemberMultiple);

        DB::commit();
        $response = flash_message("ทำรายการสำเร็จ", true);

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $member = $this->member->find($id);
        $zoneMember = $this->zoneMember->where('member_id', $id)->get();

        $conditions = [];
        foreach ($zoneMember ?? [] as $key => $value) {
            $conditions[] = ['id', '!=', $value->zone_id];
        }

        $response['member'] = $member;
        $response['zones'] = $this->zone->getDataZoneNotInZoneMember($conditions);
        $response['zone_members'] = $this->zoneMember
            ->where('member_id', $id)
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.member.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        $request->validate([
            'username' => 'unique:members',            
            'email' => 'required|email',
            'name' => 'required'
        ]);

        $member = $this->member->find($id);
        if ($request->input('password')) {

            $member->password = md5($request->input('password'));
        }
        $member->name = $request->input('name');
        $member->email = $request->input('email');
        $member->phone = $request->input('phone');
        $member->line_id = $request->input('line_id');
        $member->role = $request->input('role');
        $member->zone_id = $request->input('zone_id');

        if ($member->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = flash_message();

        $member = $this->member->find($id);
        if ($member->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     * 
     * 
     */
    public function delete(Request $request, $id)
    {
        $response = flash_message();

        $member = $this->member->find($id);

        if ($request->has('role') && $request->input('role') == '1') {

            if ($member->forceDelete()) {

                \App\MemberLogin::where('member_id', $member->id)->forceDelete();
                
                $response = flash_message("ทำรายการสำเร็จ", true);
            }

        } else {
   
            if ($member->forceDelete()) {

                $this->zoneMember->where('member_id', $member->id)->forceDelete();
                
                $response = flash_message("ทำรายการสำเร็จ", true);
            }
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     * 
     */
    public function reSyncMemberZone()
    {
        $members = $this->member
            ->where('type', 'clinic')
            ->where('role', 1)
            ->get();

        foreach ($members as $member) {

            $this->zoneMember->where('member_id', $member->id)->delete();

            $erpContent = json_decode($member->content, true);

            $zone = $this->zone->where('name', $erpContent['user_id'][1])->first();

            if (!empty($zone)) {

                $this->zoneMember->insert([
                    'zone_id' => $zone->id,
                    'member_id' => $member->id
                ]);
            }
        }

        $response = flash_message("ทำการ sync โวนในระบบแอปใหม่เรียบร้อยแล้ว", true);

        return redirect()->back()->with('message', $response);
    }


    public function lineWebHook(){

        $strAccessToken = env('LINE_ACCESS_TOKEN');

        $content = file_get_contents('php://input');
        $arrJson = json_decode($content, true);

        $strUrl = "https://api.line.me/v2/bot/message/reply";

        $arrHeader = array();
        $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $strAccessToken);


        $line_secret_code = $arrJson['events'][0]['message']['text'];
        $line_user_id = $arrJson['events'][0]['source']['userId'];

        $tmp_text = array();
        $select_member = $this->member
            ->where('line_secret_code',  $line_secret_code)
            ->get();

        // Line secret ตรงกับข้อมูลอันใดอันหนึ่ง
        if(!empty($select_member[0])){
            // ตรวจสอบ Line secret ว่ามีการ registered ไปหรือยัง
            if(empty($select_member[0]['line_user_id']) && ($select_member[0]['role']!=1) && (empty($select_member[0]['deleted_at']))){
                DB::table('members')
                    ->where('id', $select_member[0]->id)
                    ->update([
                        'line_user_id' => $line_user_id,
                ]);
                $tmp_text = "Line ของท่านได้เชื่อมต่อกับระบบเรียบร้อยแล้ว";
            }
            else{
                $tmp_text = "Validation Code ไม่ถูกต้อง";
            }
            

            // $tmp_text = ['status' => 'OK', 'member' => $select_member[0], 'line_secret_code' => $line_secret_code, 'line_user_id' => $line_user_id];
        }    
        else{
            $tmp_text = "Validation Code ไม่ถูกต้อง";
        }
        
        $arrPostData = array();
        $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
        $arrPostData['messages'][0]['type'] = "text";
        $arrPostData['messages'][0]['text'] =  $tmp_text;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);

    }
}
