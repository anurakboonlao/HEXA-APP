<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Member;
use App\MemberLogin;
use App\Payment;
use App\DoctorContact;

use App\Zone;
use App\ZoneMember;
use App\Setting;

use DB;

use PDF;

use Validator;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Facades\Log;

use Jenssegers\Agent\Agent;

class MemberController extends Controller
{
    public $request;
    public $member;
    public $memberLogin;
    public $remoteMysql;
    public $payment;
    public $doctorContact;

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
        Setting $setting
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

        $this->erpUrl = env('ERP_HOST', 'https://hexa-marketing.netforce.co.th/xmlrpc');
        $this->erpDB = env('ERP_DATABASE', 'hexa');
        $this->erpUsername = env('ERP_USERNAME', 5654);
        $this->erpPassword = env('ERP_PASSWORD', "1234");
    }

    /**
     * 
     * 
     * 
     */
    public function getMembersToZone()
    {
        $response['members'] = [];

        $conditions = [];

        $key = $this->request->input('q') ?? '';

        if (!empty($key)) {

            $conditions[] = ['name', 'like', '%'. $key .'%'];
        }

        $members = $this->member
            ->where($conditions)
            ->where('role', 1)
            ->orderBy('name', 'asc')
            ->limit(20)
            ->get();

        foreach ($members as $key => $member) {

            /*if (!$member->in_zone()) {*/

                $response['members'][] = [
                    'id' => $member->id,
                    'text' => $member->name
                ];
            /*}*/
        }

        return response()->json([
            'results' => $response['members']
        ]);
    }

    /**
     * 
     * 
     * 
     */
    public function login()
    {
        $response = [
            'status' => false,
            'message' => "Username or password incorrect.",
            'data' => null
        ];

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $jsonData = $this->request->json()->all();

            $validator = Validator::make($jsonData, [
                'username' => 'required',
                'password' => 'required',
                'device_type' => 'required',
                //'device_token' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $findMember = $this->member->where([
                ['username', $jsonData['username']],
                ['password', md5($jsonData['password'])],
            ])
            ->first();

            if(!$findMember) { 
                
                return response()->json($response); 
            }
    
            try {

                $uuid = Uuid::uuid4();
                $uuid = $uuid->toString();
            
            } catch (UnsatisfiedDependencyException $e) {

                DB::rollback();
                $response['message'] = $e->getMessage();
                return response()->json($response);
            }

            $this->memberLogin->member_id = $findMember->id;
            $this->memberLogin->token = $uuid;
            $this->memberLogin->expire_date = user_token_expire_date();
            $this->memberLogin->device_type = $jsonData['device_type'];
            $this->memberLogin->device_token = $jsonData['device_token'] ?? "1234567890";
            $this->memberLogin->status = 1;

            if (!$this->memberLogin->save()) {

                DB::rollback();
                return response()->json($response);
            }

            $response = [
                'status' => true,
                'message' => "Success.",
                'data' => [
                    'member_id' => $findMember->id,
                    'role' => $findMember->role,
                    'token' => $this->memberLogin->token,
                    'expire_date' => $this->memberLogin->expire_date,
                    'device_type' => $this->memberLogin->device_type,
                    'device_token' => $this->memberLogin->device_token,
                    'type' => $findMember->type
                ]
            ];

            return response()->json($response);
        }

        return response()->json($response);
    }

    /**
     * 
     * 
     * 
     */
    public function socialLogin()
    {
        $response = [
            'status' => false,
            'message' => "ไม่พบข้อมูลของบัญชีนี้ในระบบ.",
            'data' => null
        ];

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $jsonData = $this->request->json()->all();

            $validator = Validator::make($jsonData, [
                //'email' => 'required',
                'device_type' => 'required',
                //'device_token' => 'required',
                'social_type' => 'required',
                'social_id' => 'required',
                'social_token' => 'required',
                //'name' => 'required',
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }
    
            try {

                $uuid = Uuid::uuid4();
                $uuid = $uuid->toString();
            
            } catch (UnsatisfiedDependencyException $e) {

                DB::rollback();
                $response['message'] = $e->getMessage();
                return response()->json($response);
            }

            $memberData = [];

            $jsonData['email'] = (empty($jsonData['email'])) ? $jsonData['social_id'] . "@hexaceram.com" : $jsonData['email'];

            $findMember = $this->member->where('email', $jsonData['email'])->first();

            if(!$findMember) { 

                $this->member->email = $jsonData['email'];
                $this->member->name = $jsonData['name'];
                $this->member->role = 1;
                $this->member->username = $jsonData['email'];
                $this->member->password = md5('Aa123456');

                if (!$this->member->save()) {

                    $response = [
                        'status' => false,
                        'message' => "มีบางอย่างผิดพลาด กรุณาลองใหม่",
                        'data' => null
                    ];
                    
                    DB::rollback();
                    return response()->json($response);
                }

                $memberData = $this->member;
            }

            $this->memberLogin->member_id = (!$findMember) ? $memberData->id : $findMember->id;
            $this->memberLogin->token = $uuid;
            $this->memberLogin->expire_date = user_token_expire_date();
            $this->memberLogin->device_type = $jsonData['device_type'];
            $this->memberLogin->device_token = $jsonData['device_token'] ?? "1234567890";
            $this->memberLogin->status = 1;
            $this->memberLogin->social_type = $jsonData['social_type'];
            $this->memberLogin->social_id = $jsonData['social_id'];
            $this->memberLogin->social_token = $jsonData['social_token'];

            if (!$this->memberLogin->save()) {

                DB::rollback();
                return response()->json($response);
            }

            $response = [
                'status' => true,
                'message' => "Success.",
                'data' => [
                    'member_id' => (!$findMember) ? $memberData->id : $findMember->id,
                    'role' => (!$findMember) ? $memberData->role : $findMember->role,
                    'token' => $this->memberLogin->token,
                    'expire_date' => $this->memberLogin->expire_date,
                    'device_type' => $this->memberLogin->device_type,
                    'device_token' => $this->memberLogin->device_token,
                    'type' => (!$findMember) ? null : $findMember->type
                ]
            ];

            return response()->json($response);
        }

        return response()->json($response);
    }

    /**
     * 
     * 
     * 
     */
    public function checkVerifyKey()
    {
        $response = [
            'status' => false,
            'message' => "ยังไม่ได้ทำการยืนยันตัวตน Invite Code",
            'data' => null
        ];

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }
                
            $customerVerify = $memberLogin->member->customer_verify_key;

            if (!$customerVerify) {

                return response()->json($response);
            }

            $response = [
                'status' => true,
                'message' => "ยืนยันตัวตน Invite Code แล้ว",
                'data' => [
                    'member_id' => $memberLogin->member->id,
                    'customer_verify_key' => $memberLogin->member->customer_verify_key
                ]
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getImagePopUpAds()
    {
        $response = [
            'status' => false,
            'message' => "ไม่พบรูปภาพ Pop-up Ads.",
            'data' => null
        ];

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }
                
            $setting = $this->setting->first();

            if (!$setting->shipping_cover_page) {
                return response()->json($response);
            }

            $response = [
                'status' => true,
                'message' => "Success.",
                'data' => [
                    'image' => ($setting->shipping_cover_page) ? store_image($setting->shipping_cover_page) : null
                ]
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getSettingContact()
    {
        $response = [
            'status' => false,
            'message' => "Not found.",
            'data' => null
        ];

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }
                
            $setting = $this->setting->first();

            if (!$setting->hexa_line) {
                return response()->json($response);
            }

            $response = [
                'status' => true,
                'message' => "Success.",
                'data' => [
                    'hexa_line' => $setting->hexa_line,
                    'hexa_email' => $setting->hexa_email,
                    'hexa_facebook' => $setting->hexa_facebook,
                    'dental_supply_link' => $setting->dental_supply_link
                ]
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     * 
     */
    public function inviteCode()
    {
        $response = [
            'status' => false,
            'message' => "Not found.",
            'data' => null
        ];

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $jsonData = $this->request->json()->all();

            $validator = Validator::make($jsonData, [
                'member_id' => 'required',
                'invite_code' => 'required'
            ]);
        
            if ($validator->fails()) {
        
                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }
        
            $member = $this->member->find($jsonData['member_id']);
        
            if (!$member) {
        
                $response = [
                    'status' => false,
                    'message' => "member_id ไม่ถูกต้อง",
                ];
        
                return response()->json($response);
            }

            $erpData = [];
            
            if (!is_numeric($jsonData['invite_code'])) { // check invite code is number ( false = 'clinic' , true = 'doctor' )

                $erpData = $this->findContactErp($jsonData['invite_code']);
                
            } else {

                $erpData = $this->findDoctorErp($jsonData['invite_code']);
                $erpData['categ_id'][0] = 5;
            }

            if (empty($erpData['code'])) {

                $response = [
                    'status' => false,
                    'message' => "Invite Code ไม่ถูกต้อง",
                ];

                return response()->json($response);
            }
            
            //dd($erpData);

            $member->customer_id = $erpData['id'];
            $member->customer_verify_key = $erpData['code'];
            $member->province_id = $erpData['province_id'][0] ?? 0;
            $member->name = $erpData['name'] ?? '';
            $member->phone = $erpData['phone'] ?? '';
            $member->address = $erpData['address'] ?? '';
            $member->type = ($erpData['categ_id'][0] == 5) ? 'doctor' : 'clinic'; //get_member_type($verifyCustomer->cus_nick);
            $member->content = json_encode($erpData);
            $member->eo_cus_id = $erpData['eo_cus_id'] ?? 0;

            if ($member->save()) {

                if($erpData['categ_id'][0] != 5) {

                    $zone = $this->zone->where('name', $erpData['user_id'][1])->first();

                    if (empty($zone)) {

                        $this->zone->id = $erpData['user_id'][0];
                        $this->zone->name = $erpData['user_id'][1];
                        $this->zone->save();
                    }

                    $this->zoneMember->zone_id = (empty($zone)) ? $this->zone->id : $zone->id;
                    $this->zoneMember->member_id = $member->id;
                    $this->zoneMember->created_at = date('Y-m-d H:i:s');
                    $this->zoneMember->updated_at = date('Y-m-d H:i:s');

                    $this->zoneMember->save();
                }
                
                $response = [
                    'status' => true,
                    'message' => "คุณได้ทำการยืนยัน Invite Code สำเร็จแล้ว",
                    'data' => [
                        'member_id' => $member->id,
                        'role' => $member->role,
                        'type' => $member->type
                    ]
                ];

                return response()->json($response);
            }
        }
        
        return response()->json($response);
    }

    /**
     * 
     * 
     * 
     */
    public function forgotPassword()
    {
        $response = [
            'status' => false,
            'message' => "Not found.",
            'data' => null
        ];

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $jsonData = $this->request->json()->all();

            $validator = Validator::make($jsonData, [
                'email' => 'required|email'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $findMember = $this->member->where([
                ['email', $jsonData['email']]
            ])
            ->first();

            if(!$findMember) {
                
                return response()->json($response); 
            }
    
            $password = $findMember->customer_id;
            $findMember->password = md5($password);

            if (!$findMember->save()) {

                return response()->json($response);
            }

            $this->sendEmailPasswordToMember($findMember, $password);

            $response = [
                'status' => true,
                'message' => "Your password has been reset. Please check in the email.",
                'data' => [
                    'username' => $findMember->username
                ]
            ];

            return response()->json($response);
        }

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = [
            'status' => false,
            'message' => "มีบางอย่างไม่ถูกต้อง กรุณาลองใหม่",
            'data' => null
        ];

        if ($this->request->wantsJson() || $this->request->isJson()) {

            try {

                $jsonData = $this->request->json()->all();

                $validator = Validator::make($jsonData, [
                    'username' => 'required|min:4|unique:members,username',
                    'cus_nick' => 'required|unique:members,customer_verify_key',
                    'email' => 'required|email|unique:members,email'
                ], [
                    'username.unique' => 'ชื่อผู้ใช้นี้ถูกใช้งานไปแล้ว',
                    'cus_nick.unique' => 'ระบบแจ้งว่า invite code นี้ได้ใช้ข้อมูลนี้ลงทะเบียนไปแล้ว',
                    'email.unique' => 'อีเมลนี้ไม่สามารถใช้งานได้'
                ]);
            
                if ($validator->fails()) {

                    $response['message'] = $validator->errors()->first();
                    return response()->json($response);
                }

                DB::beginTransaction();

                $memberCheck = $this->member->where('customer_verify_key', $jsonData['cus_nick'])->first();
                if ($memberCheck) {

                    $response['message'] = "The client has already made an identity verification transaction.";
                    return response()->json($response);
                }
                
                $verifyCustomer = $this->findContactErp($jsonData['cus_nick']);
                if(!$verifyCustomer) { 
                    
                    $verifyCustomer = $this->findDoctorErp($jsonData['cus_nick']);

                    if (!$verifyCustomer) {

                        $response['message'] = "ไม่พบข้อมูลรหัสการร้องขอนี้";
                        return response()->json($response);
                    }

                    $verifyCustomer['categ_id'][0] = 5;
                }

                /*$memberCheck = $this->member->where('customer_id', $verifyCustomer['id'])->first();
                if ($memberCheck) {

                    $response['message'] = "The client has already made an identity verification transaction.";
                    return response()->json($response);
                }*/
                
                $password = $verifyCustomer['id'];
                $this->member->username = $jsonData['username'];
                $this->member->customer_id = $verifyCustomer['id'];
                $this->member->customer_verify_key = $verifyCustomer['code'];
                $this->member->password = md5($password);
                $this->member->name = $verifyCustomer['name'];
                $this->member->email = $jsonData['email'];
                $this->member->phone = $verifyCustomer['phone'] ?? '';
                $this->member->province_id = $verifyCustomer['province_id'][0] ?? 0;
                $this->member->address = $verifyCustomer['address'] ?? '';
                $this->member->type = ($verifyCustomer['categ_id'][0] == 5) ? 'doctor' : 'clinic'; //get_member_type($verifyCustomer->cus_nick);
                $this->member->content = json_encode($verifyCustomer);
                $this->member->eo_cus_id = $verifyCustomer['eo_cus_id'] ?? 0;
                $this->member->role = 1;
                
                $point = 0;
                if ($this->member->type == 'doctor') {
                    
                    $point = $this->getDoctorPoint($this->member->customer_verify_key);
                }

                $this->member->point = $point;

                if (!$this->member->save()) {

                    DB::rollback();
                    return response()->json($response);
                }

                if ($this->member->type == 'doctor') {

                    $doctorContacts = [];
                    foreach ($verifyCustomer['contact_ids'] as $contactId) {
                        $contact = $this->findContactErp($contactId, 'id');
                        if ($contact) {
                            $doctorContacts[] = [
                                'member_id' => $this->member->id,
                                'contact_id' => $contactId,
                                'eo_cus_id' => $contact['eo_cus_id'],
                                'content' => json_encode($contact)
                            ];
                        }
                    }

                    $this->doctorContact->insert($doctorContacts);

                } else {

                    if ($verifyCustomer['user_id'][0] ?? false) {
                        $zone = $this->zone
                            ->where('name', $verifyCustomer['user_id'][1])
                            ->first();
                        
                        if (!$zone) {
                            $this->zone->insert([
                                'id' => $verifyCustomer['user_id'][0] ?? '',
                                'name' => $verifyCustomer['user_id'][1] ?? '', 
                                'description' => $verifyCustomer['user_id'][2] ?? ''                            
                            ]);
                        }

                        $zone = $this->zone
                            ->where('name', $verifyCustomer['user_id'][1])
                            ->first();
                        
                        $this->zoneMember->insert([
                            'zone_id' => $zone->id,
                            'member_id' => $this->member->id            
                        ]);
                    }
                }

                $agent = new Agent();
                $isIphone = $agent->is('iPhone');
                
                $this->sendEmailPasswordToMember($this->member, $password, $isIphone);

                $response = [
                    'status' => true,
                    'message' => "การลงทะเบียนสำเร็จ \n กรุณาตรวจสอบ Password \n ที่ Email {$this->member->email} ของท่าน",
                    'data' => [
                        'username' => $this->member->username
                    ]
                ];

                DB::commit();

                return response()->json($response);
            } catch (\Exception $e) {

                $response['message'] = "กรุณารอสักครู่ แล้วทำรายการใหม่อีกครั้ง";
                return response()->json($response);
            }
        }

        return response()->json($response);
    }

    /**
     * 
     * 
     */
    public function doctorGetContactErp()
    {
        $token = $this->request->header('token');

        $memberLogin = $this->memberLogin->findBy('token', $token);

        $doctor = $this->findDoctorErp($memberLogin->member->customer_verify_key);
        $doctorContacts = [];
        foreach ($doctor['contact_ids'] as $contactId) {
            $contact = $this->findContactErp($contactId, 'id');
            if ($contact) {
                $contact['clinic'] = $contact['name'];
                $contact['name'] = $memberLogin->member->name ?? "";
                $contact['address'] = null;
                $contact['phone'] = null;
                $doctorContacts[] = array_only($contact, ['name', 'clinic', 'address', 'phone', 'mobile']);
            }
        }

        $response = [
            'status' => true,
            'message' => "success",
            'data' => $doctorContacts
        ];

        return response()->json($response);
    }

    /**
     * 
     * 
     * 
     */
    public function getProfile()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);
            $zoneMember = $this->zoneMember->where('member_id', $memberLogin->member_id)->first();

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $point = 0;
            if ($memberLogin->member->type == 'doctor') {
                
                $point = $this->getDoctorPoint($memberLogin->member->customer_verify_key);
            }

            $response = flash_message('success', true);
            $response['data']['profile'] = [
                'id' => $memberLogin->member->id,
                'username' => $memberLogin->member->username,
                'name' => $memberLogin->member->name,
                'image' => ($memberLogin->member->image) ? upload_image('members/' . $memberLogin->member->image) : null,
                'email' => $memberLogin->member->email,
                'emailNoti' => $memberLogin->member->emailNoti,
                'phone' => $memberLogin->member->phone,
                'phoneNoti' => $memberLogin->member->phoneNoti,
                'province_id' => $memberLogin->member->province_id,
                'address' => $memberLogin->member->address,
                'role' => $memberLogin->member->role,
                'notification' => $memberLogin->member->notification,
                'point' => $point,
                'zone' => $zoneMember->zone->name ?? null
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     * 
     */
    public function updateImageProfile()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->isMethod('post')) {

            $requestData = $this->request->all();

            $validator = Validator::make($requestData, [
                'image' => 'required|image'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $member = $this->member->find($memberLogin->member->id);
            if ($this->request->hasFile('image') && $this->request->file('image')->isValid()) {
                
                $upload = $this->uploadImage($this->request->file('image'), 'members');
                if (!$upload['status']) {

                    $response['message'] = $upload['file_name'];
                    return response()->json($response);
                }
                
                $member->image = $upload['file_name'];
            }

            if (!$member->save()) {

                $response['message'] = 'Can not upload image.';
                return response()->json($response);
            }
            
            $response = [
                'status' => true,
                'message' => "success."
            ];
            
            $response['data']['image'] = upload_image('members/' . $member->image);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function updateProfile()
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
                'name' => 'required',
                'email' => 'required|email',
                'emailNoti' => 'required|email',
                'phone' => 'required',
                'phoneNoti' => 'required|min:10|max:10|numeric',
                'province_id' => 'required',
                'notification' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $member = $this->member->find($memberLogin->member->id);
            $member->name = $jsonData['name'];
            $member->email = $jsonData['email'];
            $member->emailNoti = $jsonData['emailNoti'];
            $member->phone = $jsonData['phone'];
            $member->phoneNoti = $jsonData['phoneNoti'];
            $member->province_id = $jsonData['province_id'];
            $member->notification = $jsonData['notification'];

            if (!$member->save()) {

                return response()->json($response);
            }
            
            $response = flash_message('success', true);
            $response['data']['profile'] = [
                'id' => $member->id,
                'username' => $member->username,
                'name' => $member->name,
                'image' => ($member->image) ? upload_image('members/' . $member->image) : null,
                'email' => $member->email,
                'emailNoti' => $member->emailNoti,
                'phone' => $member->phone,
                'phoneNoti' => $member->phoneNoti,
                'province_id' => $member->province_id,
                'address' => $member->address,
                'role' => $member->role,
                'notification' => $member->notification,
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function changePassword()
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
                'new_password' => 'required|min:6|max:20',
                'confirm_new_password' => 'required|same:new_password',
                'old_password' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $member = $this->member->find($memberLogin->member->id);

            if ($member->password !== md5($jsonData['old_password'])) {

                $response['message'] = "Old password does not match.";
                return response()->json($response);
            }

            $member->password = md5($jsonData['confirm_new_password']);

            if (!$member->save()) {

                $response['message'] = "Can not change password !";
                return response()->json($response);
            }
            
            $response = flash_message('Changed password successfully.', true);
            $response['data'] = null;

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function logout()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token') ?? '';

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            if (!$memberLogin->delete()) {

                $response['message'] = 'เกิดข้อผิดพลาดไม่สามารถออกจากระบบได้ กรุณาเคลียแคลชแอปแล้วเข้าใหม่อีกครั้ง';
                return response()->json($response);
            }
            
            $response = flash_message('success', true);

            return response()->json($response);
        }
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
    public function getDoctorPoint($code)
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
            ['code', 'name', 'clinic', 'contact_ids', 'point', 'claim_point', 'paid_amount', 'amount_point', 'total_point']
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

        $totalPointRedeemed = 0;
        $member = $this->member->where('customer_verify_key', $code)->first();
        if ($member) {

            $totalPointRedeemed = $member->totalPointRedeemed();
        }
        
        $balancePoint = @floor($response[0]['point'] + $response[0]['claim_point']) ?? 0;

        return ($balancePoint);
    }

    /**
     * 
     * 
     */
    public function getMemberInvoices()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }
            
            $jsonData = $this->request->json()->all();
            $type = $jsonData['type'] ?? 'all';
            $ids = $jsonData['ids'] ?? [];
            
            $db = $this->erpDB;
            $username = $this->erpUsername;
            $password = $this->erpPassword;
            $tableName = "account.bill";
            $method = "search_read";
            
            $searchConditions = [
                ['contact_id', '=', $memberLogin->member->customer_id],
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
            foreach ($bills as $bill) {
                if ($this->payment->where('bill_ids', 'like', '%'. $bill['id'] .'%')->where('confirmed', 1)->count() == 0) {
                    $billData[] = $bill;
                }
            }

            $response['data'] = map_bill_issue_response($billData, $type, $ids);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getInvoiceDetail()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $params = $this->request->all();
            
            $db = $this->erpDB;
            $username = $this->erpUsername;
            $password = $this->erpPassword;
            $tableName = "account.bill";
            $method = "search_read";
            
            $searchConditions = [
                ['id', '=', $params['invoice_id']],
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

            $response = flash_message('success', true);
            $response['data']['bill'] = $bill;
            $response['data']['invoices'] = $invoices;

            $response['data']['bill']['pdf_url'] = url('/') . '/bills/' . $fileName;

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getInvoiceDetailPDF($invoiceId)
    {
        //return response()->file(public_path('template/files/bill_issue.pdf'));

        $response = [];
        
        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "account.bill";
        $method = "search_read";
        
        $searchConditions = [
            ['id', '=', $invoiceId],
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

        $invoices = $client->call(
            'execute', 
            'account.bill.line', 
            $method, 
            [
                ['bill_issue_id', '=', $bill[0]['id']]
                ,
                []
            ], 
            $opt, 
            $db, 
            $username, 
            ""
        );

        if (array_key_exists('faultCode', $invoices)) {

            $invoices = [];
        }

        /*$host = "http://hexa-api.netforce.co.th";
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
        
        $response = flash_message('success', true);
        $response['data']['bill'] = $bill;
        $response['data']['invoices'] = $invoices;

        $fileName = "bill-" . $bill['id'] . '.pdf';
        $fullUrl = $host . $url;

        $response['data']['bill']['pdf_url'] = $host . $url;*/
        
        $response = flash_message('success', true);
        $response['bill'] = $bill[0];
        $response['bill']['pdf_url'] = url('/') . '/bill/' . $bill[0]['id'] .'?type=pdf';
        $response['invoices'] = $invoices;

        $pdf = PDF::loadView('invoice.pdf', $response);
        return $pdf->setPaper('a4', 'portrait')->stream($response['bill']['number'] . '.pdf');

        return view('invoice.pdf', $response);
    }

    /**
     * 
     * 
     */
    public function getMemberPaidYear($code, $status = 'done', $conditions = [])
    {
        $response = [];
        
        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "account.bill";
        $method = "search_read";

        $searchConditions = [
            ['contact_id', '=', $code],
            ['state', '=', $status],
            ['date', '>=', '2019-01-01'],
            ['date', '<=', '2019-12-31'],
        ];

        $condArgs = [
            $searchConditions,
            ['']
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

        return response()->json($response);
    }

    /**
     * 
     * 
     */
    public function bankTranferPayment()
    {
        $response = flash_message('The authentication has failed please login again.');

        $token = $this->request->header('token');

        $memberLogin = $this->memberLogin->findBy('token', $token);

        if (!$memberLogin) {

            return response()->json($response);
        }
        
        $requestData = $this->request->input();

        $validator = Validator::make($requestData, [
            'bank' => 'required',
            'date' => 'required',
            'time' => 'required',
            'amount' => 'required'
        ]);
        
        if ($validator->fails()) {

            $response['message'] = $validator->errors()->first();
            return response()->json($response);
        }
        
        $type = 'all';
        $ids = explode(',', $requestData['ids']);
        
        if (count($ids) < 1) {
            
            $response['message'] = 'Please select invoice.';
            return response()->json($response);
        }

        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "account.bill";
        $method = "search_read";
        
        $searchConditions = [
            ['contact_id', '=', $memberLogin->member->customer_id],
            ['state', '=', 'confirmed']
            //['invoiced', '=', true]
        ];

        $condArgs = [
            $searchConditions,
            []
        ];
        
        $opt = [];
        
        $client = new \App\Services\XmlrpcClient($this->erpUrl);
        $invoices = $client->call(
            'execute', 
            $tableName, 
            $method,
            $condArgs, 
            $opt, 
            $db,
            $username, 
            ""
        );
        
        $invoiceData = map_bill_issue_response($invoices, $type, $ids);

        if ($this->request->hasFile('file')) {

            $upload = $this->uploadFile($this->request->file('file'), 'payments');

            if ($upload['status']) {

                $this->payment->file = $upload['file_name'];
            }
        }

        $this->payment->member_id = $memberLogin->member_id;
        $this->payment->type = 'bank_tranfer';
        $this->payment->bank_name = $requestData['bank'];
        $this->payment->tranfer_date = $requestData['date'];
        $this->payment->tranfer_time = $requestData['time'];
        $this->payment->amount_total = $invoiceData['amount_total_number'] ?? 0;
        $this->payment->bill_ids = $requestData['ids'];
        $this->payment->bill_content = json_encode($invoiceData);
        $this->payment->ip = request()->ip();
        $this->payment->full_step = 1;
        
        if ($this->payment->save()) {

            $accountings = $this->member->where('role', 2)->get();
            foreach ($accountings ?? [] as $accounting) {
                foreach ($accounting->logins ?? [] as $login) {
                    $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                        'title' => 'Payment Notification',
                        'message' => 'มีรายการแจ้งชำระเงินใหม่'
                    ]);
                }
            }
            
            $response = flash_message('success', true);
        }

        return response()->json($response);
    }
    
    /**
     * 
     * 
     */
    public function creditPayment()
    {
        $response = flash_message('The authentication has failed please login again.');

        $token = $this->request->header('token');

        $memberLogin = $this->memberLogin->findBy('token', $token);

        if (!$memberLogin) {

            return response()->json($response);
        }
        
        $jsonData = $this->request->input();
        
        $type = 'all';
        $ids = explode(',', $jsonData['ids']);
        
        if ($jsonData['ids'] == '') {
            
            $response['message'] = 'Please select invoice.';
            return response()->json($response);
        }

        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "account.bill";
        $method = "search_read";
        
        $searchConditions = [
            ['contact_id', '=', $memberLogin->member->customer_id],
            ['state', '=', 'confirmed'],
            //['invoiced', '=', true]
        ];

        $condArgs = [
            $searchConditions,
            []
        ];
        
        $opt = [];
        
        $client = new \App\Services\XmlrpcClient($this->erpUrl);
        $invoices = $client->call(
            'execute', 
            $tableName, 
            $method,
            $condArgs, 
            $opt, 
            $db,
            $username, 
            ""
        );
        
        $invoiceData = map_bill_issue_response($invoices, $type, $ids);

        if (count($invoiceData) == 0) {

            $response['message'] = 'Please select invoice.';
            return response()->json($response);
        }

        $this->payment->member_id = $memberLogin->member_id;
        $this->payment->type = 'credit';
        $this->payment->bank_name = '';
        $this->payment->tranfer_date = date('Y-m-d');
        $this->payment->tranfer_time = date('H:i');
        $this->payment->amount_total = $invoiceData['amount_total_number'] ?? 0;
        $this->payment->bill_ids = $jsonData['ids'];
        $this->payment->bill_content = json_encode($invoiceData);
        $this->payment->ip = request()->ip();
        $this->payment->full_step = 0;
        
        if ($this->payment->save()) {

            $response = flash_message('success', true);
            $response['url'] = url('/') . '/payment/' . $this->payment->id;
        }

        return response()->json($response);
    }

    /**
     * 
     * 
     */
    public function updateZone()
    {
        $params = $this->request->all();

        $logContent = http_build_query($params);

        Log::info($logContent);

        try {
            $members = $this->member
                ->where('type', 'clinic')
                //->where('role', 1)
                ->where('customer_id', 'like', '%' . $params['contact_id'] . '%')
                ->get();
            
            $zone = $this->zone
                ->where('name', $params['zone_name'])
                ->orWhere('id', $params['zone_id'])
                ->first();

            if (empty($zone)) {

                $this->zone->id = $params['zone_id'];
                $this->zone->name = $params['zone_name'];
                $this->zone->save();

                $zone = $this->zone->find($this->zone->id);
            }

            foreach ($members as $member) {
                
                $this->zoneMember->where('member_id', $member->id)->delete();    
                $this->zoneMember->insert([
                    'zone_id' => $zone->id,
                    'member_id' => $member->id
                ]);
            }
            
            $response = flash_message('success', true);
            
            return response()->json($response);

        } catch (\Exception $e) {

            $response = flash_message($e->getMessage());

            Log::info(json_encode($response));
            
            return response()->json($response);
        }
    }
}
