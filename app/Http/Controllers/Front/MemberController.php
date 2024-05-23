<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Psr7\Response;

use App\Http\Controllers\Controller;
use App\Member;
use App\MemberLogin;
use App\Payment;
use App\DoctorContact;

use App\Zone;
use App\ZoneMember;
use App\Setting;
use App\Promotion;
use App\RetainerGallery;

use App\OrderPickup;

use DB;

use PDF;

use Validator;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Support\Facades\Log;

use Auth;
use Session;

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
    public $promotion;
    public $retainerGallery;

    public $erpUrl;
    public $erpDB;
    public $erpUsername;
    public $erpPassword;

    public $orderPickup;

    public function __construct(
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Payment $payment,
        DoctorContact $doctorContact,
        Zone $zone,
        ZoneMember $zoneMember,
        OrderPickup $orderPickup,
        Setting $setting,
        Promotion $promotion,
        RetainerGallery $retainerGallery
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
        $this->promotion = $promotion;
        $this->retainerGallery = $retainerGallery;

        $this->orderPickup = $orderPickup;

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
    public function signin()
    {
        $response = [];

        $response['retainer_galleries'] = $this->retainerGallery
            ->where('public', 1)
            ->orderBy('sort', 'asc')
            ->get();

        return view('front.signin', $response);
    }

    /**
     *
     *
     *
     */
    public function postSignin()
    {
        $response = flash_message("ยืนยันตัวตนไม่สำเร็จ", false);

        $this->request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $params = $this->request->all();

        $member = $this->member
            ->where('username', $params['username'])
            ->where('password', md5($params['password']))
            ->first();

        if (!$member) {

            return redirect()->back()->with('message', $response);
        }

        Session::put('member', $member);

        return redirect('front/dashboard');
    }

    /**
     *
     *
     *
     */
    public function dashboard()
    {
        $response = [];

        $member = $this->member->find($this->request->member->id);

        $orderPickups = $this->orderPickup
            ->where([
                ['member_id', $member->id],
                ['checked', 0]
            ])
            ->orderBy('id', 'desc')
            ->get();

        $response['promotions'] = $this->promotion
            ->where([
                ['public', 1],
                ['type', 2]
            ])
            ->orderBy('sort', 'asc')
            ->get();

        $response['setting'] = $this->setting->first();
        $response['order_pickups'] = $orderPickups;

        return view('front.dashboard', $response);
    }

    /***
     *
     *
     */
    public function profile(Request $request, $id)
    {
        $response = [
            'message' => [
                'status' => false,
                'message' => "Errors."
            ]
        ];

        $member = $this->member->find($id);
        $response['member'] = $member;

        if ($this->request->isMethod('post')) {
            $params = $this->request->all();

            if ($this->request->hasFile('image')) {
                $upload = $this->uploadImage($this->request->file('image'), 'members');
                if (!$upload['status']) {
                    $response['message']['message'] = $upload['file_name'];
                    return redirect()->back()->with('message', $response['message']);
                }

                $member->image = $upload['file_name'];
            }

            $validator = Validator::make($params, [
                'emailNoti' => 'required|email',
                'phoneNoti' => array('required','numeric'),
            ]);

            if ($validator->fails()) {
                $response['message'] = [
                    'status' => false,
                    'message' => "แก้ไขข้อมูลผิดพลาด"
                ];
                return redirect()->back()->with('message', $response['message']);
            }
        

            $member->username = $params['username'] ?? $member->username;
            $member->email = $params['email'] ?? $member->email;
            $member->emailNoti = $params['emailNoti'];
            $member->phone = $params['phone'] ?? $member->phone;
            $member->phoneNoti = $params['phoneNoti'];
            $member->name = $params['name'] ?? $member->name;
            $member->address = $params['address'] ?? $member->address;
            $member->notification = $params['notification'] ?? 0;
            $member->line_id = $params['line_id'] ?? $member->line_id;
            
            if ($member->save()) {
                $response['message'] = [
                    'status' => true,
                    'message' => "แก้ไขข้อมูลส่วนตัว เรียบร้อย"
                ];
            }

            return redirect()->back()->with('message', $response['message']);
        }

        return view('front.member.profile', $response);
    }

    /**
     *
     *
     */
    public function changePassword(Request $request, $id)
    {
        $response = [];

        $member = $this->member->find($id);

        if ($this->request->isMethod('post')) {

            $params = $this->request->all();

            $validator = Validator::make($params, [
                'password' => 'required',
                'password_confirmation' => 'required'
            ]);

            if ($validator->fails()) {

                $response = [
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ];

                return redirect()->back()->with('message', $response);
            }

            if ($params['password'] != $params['password_confirmation']) {

                $response['message'] = [
                    'status' => false,
                    'message' => "Password and password confirmation must be the same."
                ];

                return redirect()->back()->with('message', $response['message']);
            }

            $member->password = md5($params['password']);
            if ($member->save()) {
                $response['message'] = [
                    'status' => true,
                    'message' => "Your password changed please logout and login again."
                ];
            }

            return redirect()->back()->with('message', $response['message']);
        }

        return view('front.member.change_password', $response);
    }

    /**
     *
     * Logout Destroy session
     */
    public function logout()
    {
        Session::forget('member');

        return redirect('front');
    }

    /**
     *
     *
     *
     */
    public function inviteCode(Request $request)
    {
        $response = [];

        $param = $this->request->all();

        $validator = Validator::make($param, [
            'member_id' => 'required',
            'invite_code' => 'required'
        ]);

        $member = $this->member->find($param['member_id']);

        if (!$member) {

            $response = [
                'status' => false,
                'message' => "member_id ไม่ถูกต้อง",
            ];

            return redirect()->back()->with('message', $response);
        }

        $erpData = [];

        if (!is_numeric($param['invite_code'])) { // check invite code is number ( false = 'clinic' , true = 'doctor' )

            $erpData = $this->findContactErp($param['invite_code']);

        } else {

            $erpData = $this->findDoctorErp($param['invite_code']);
            $erpData['categ_id'][0] = 5;
        }

        if (empty($erpData['code'])) {

            $response = [
                'status' => false,
                'message' => "Invite Code ไม่ถูกต้อง",
            ];

            return redirect()->back()->with('message', $response);
        }

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

            //update Session after invite Code pass
            Session::put('member', $member);
            $response = flash_message('คุณได้ทำการยืนยัน Invite Code สำเร็จแล้ว', true);

            return redirect()->back()->with('message', $response);
        }

        return redirect()->back()->with('message', $response);
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
     * Get Doctor Address Contact all
     *
     */
    public function doctorGetContactErp($memberId)
    {
        $memberLogin = $this->member->find($memberId);

        $doctor = $this->findDoctorErp($memberLogin->customer_verify_key);
        $doctorContacts = [];
        foreach ($doctor['contact_ids'] as $contactId) {
            $contact = $this->findContactErp($contactId, 'id');
            if ($contact) {
                $contact['id'] = $contact['id'];
                $contact['clinic'] = $contact['name'];
                $contact['name'] = $memberLogin->name ?? "";
                $contact['address'] = $contact['address'];
                $contact['phone'] = null;
                $doctorContacts[] = array_only($contact, ['id', 'name', 'clinic', 'address', 'phone', 'mobile']);
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
     * Get member ( balance_due_total, reward_point_total )
     */
    public function getHomeMemberData($id)
    {
        $response = [];

        $memberLogin = $this->member->find($id);

        if (!$memberLogin) {

            return response()->json($response, 401);
        }

        $db = $this->erpDB;
        $username = $this->erpUsername;
        $password = $this->erpPassword;
        $tableName = "account.bill";
        $method = "search_read";

        $searchConditions = [
            ['contact_id', '=', $memberLogin->customer_id],
            ['state', '=', 'confirmed']
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

        $billData = map_bill_issue_response($bills, 'all', []);

        $usedTotalPointRedemption = $memberLogin->totalPointRedeemed();
        $usedTotalPointVoucherCart = $memberLogin->totalPointVoucherCart();

        $usePointTotal = ($usedTotalPointRedemption + $usedTotalPointVoucherCart);

        $response = flash_message('success', true);
        $response['balance_due_total'] = $billData['amount_total'];
        $response['reward_point_total'] = ($memberLogin->type == 'doctor') ? ($this->getDoctorPoint($memberLogin->customer_verify_key) - $usePointTotal) : 0 ;

        return response()->json($response);
    }

    /**
     * Reset password
     *
     *
     */
    public function forgotPassword(Request $request)
    {
        $response = [
            'status' => false,
            'message' => "Not found.",
            'data' => null
        ];

        $params = $this->request->all();

        $validator = Validator::make($params, [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {

            $response['message'] = $validator->errors()->first();
            return redirect()->back()->with('message', $response);
        }

        $findMember = $this->member->where([
            ['email', $params['email']]
        ])
        ->first();

        if(!$findMember) {

            return redirect()->back()->with('message', $response);
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

        return redirect()->back()->with('message', $response);
    }

    /**
     * For request to online ordering app.
     *
     *
     */
    public function postSigninOnlineOrdering()
    {
        $online_ordering_url = env('ONLINE_ORDERING_WEB_URL', '');
        $online_ordering_api_url = env('ONLINE_ORDERING_API_URL', '');
        $member = Session::get('member');

        $client = new Client();
        $path = "/account/externallogin";
        $endpoint = $online_ordering_api_url . $path;
        $token = env('ONLINE_ORDERING_SECRET', '');


        Log::info('externallogin member log');
        Log::info($member->customer_id);
        Log::info($member->type);

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'auth-key' => $token
            ],
            'json' => [
                'contactId' => $member->customer_id,
                'type' => $member->type
            ]
        ];

        try {

            $client = new Client();
            $response = $client->post($endpoint, $options);
            // echo $response->getStatusCode();
            if($response->getStatusCode() !== 200) {
                $user_auth = json_decode($response, true);
                $errors   = $user_auth['errors'][0];
                return redirect()->back()->with('message', flash_message("You don't have permission to access online ordering application, please contact admnistrator", false));
            } else {
                $user_auth = json_decode($response->getBody(), true);
                $user_token = $user_auth['token'];
                return redirect()->away($online_ordering_url . '/' . $user_token);
            }

        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return redirect()->back()->with('message', flash_message("You don't have permission to access online ordering application, please contact admnistrator", false));
        }

        // if($response->getStatusCode() !== 200) {
        //     $user_auth = json_decode($response, true);
        //     $errors   = $user_auth['errors'][0];
        //     // Log::error($errors);
        //     //echo $errors;
        //     return redirect()->back()->with('message', flash_message("You don't have permission to access online ordering application, please contact admnistrator", false));
        // } else {
        //     $user_auth = json_decode($response->getBody(), true);
        //     $user_token = $user_auth['token'];
        //     //echo $online_ordering_url . '/' . $user_token;
        //     // Redirect to online ordering app if success
        //     return redirect()->away($online_ordering_url . '/' . $user_token);
        // }

    }

    public function postSigninOnlineOrderingSit()
    {
        $online_ordering_url = env('ONLINE_ORDERING_SIT_WEB_URL', '');
        $online_ordering_api_url = env('ONLINE_ORDERING_SIT_API_URL', '');
        $member = Session::get('member');

        $client = new Client();
        $path = "/account/externallogin";
        $endpoint = $online_ordering_api_url . $path;
        $token = env('ONLINE_ORDERING_SECRET', '');

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'auth-key' => $token
            ],
            'json' => [
                'contactId' => $member->customer_id,
                'type' => $member->type
            ]
        ];

        try {

            $client = new Client();
            $response = $client->post($endpoint, $options);
            // echo $response->getStatusCode();
            if($response->getStatusCode() !== 200) {
                $user_auth = json_decode($response, true);
                $errors   = $user_auth['errors'][0];
                return redirect()->back()->with('message', flash_message("You don't have permission to access online ordering application, please contact admnistrator", false));
            } else {
                $user_auth = json_decode($response->getBody(), true);
                $user_token = $user_auth['token'];
                return redirect()->away($online_ordering_url . '/' . $user_token);
            }

        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return redirect()->back()->with('message', flash_message("You don't have permission to access online ordering application, please contact admnistrator", false));
        }

    }
}
