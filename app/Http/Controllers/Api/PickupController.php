<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Setting;
use App\Member;
use App\MemberLogin;
use App\OrderPickup;
use App\Product;
use App\ZoneMember;

use DB;

use Validator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class PickupController extends Controller
{
    public $request;
    public $setting;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $orderPickup;
    public $product;
    public $eorderUrl;
    public $httpRequest;
    public $zoneMember;

    public function __construct(
        Setting $setting,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        OrderPickup $orderPickup,
        Product $product,
        ZoneMember $zoneMember
    ) {
        $this->setting = $setting;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->orderPickup = $orderPickup;
        $this->product = $product;
        $this->eorderUrl = env('EORDER_URL', 'http://e-order.netforce.co.th/testeorder/eorder/api.php');
        $this->httpRequest = new Client();
        $this->zoneMember = $zoneMember;
    }

    /**
     * 
     * 
     */
    public function home()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);
            
            if (!$memberLogin) {
                
                return response()->json($response);
            }

            if ($memberLogin->member->type != 'clinic') {

                $response['message'] = "Error access denied.";
                return response()->json($response);
            }

            $orderPickups = $this->orderPickup
                ->where([
                    ['member_id', $memberLogin->member_id],
                    ['checked', 0]
                ])
                ->orderBy('id', 'desc')
                ->get();
            
            $response = flash_message('success', true);
            $response['order_pickups'] = map_response_pickups($orderPickups);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getLocations()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $response = flash_message('success', true);
            $response['locations'] = [
                [
                    'location' => $memberLogin->member->name .' '. $memberLogin->member->address
                ]
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getBranches()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $branches = $this->remoteMysql->table('branch')->where([
                ['active', 0]
            ])->get();

            $response = flash_message('success', true);
            $response['branches'] = [];

            foreach ($branches as $key => $value) {
                $response['branches'][] = [
                    'id' => $value->branchid,
                    'name' => $value->branch_name .' ('. $value->branch_mac5db .')' 
                ];
            }

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
                'lat' => 'required',
                'long' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $this->orderPickup->member_id = $memberLogin->member_id;
            $this->orderPickup->address = $jsonData['location'];
            $this->orderPickup->time = $jsonData['time'];
            $this->orderPickup->branch_id = $jsonData['branch_id'] ?? 0;
            $this->orderPickup->lat = $jsonData['lat'] ?? 0;
            $this->orderPickup->long = $jsonData['long'] ?? 0;
            $this->orderPickup->clinic_note = $jsonData['clinic_note'] ?? '';
            $this->orderPickup->total_case = $jsonData['total_case'] ?? 0;
            $this->orderPickup->urgent_time = $jsonData['urgent_time'] ?? 'ด่วนมาก ' . date('d-m-Y H:i');
            $this->orderPickup->doctor_name = $jsonData['doctor_name'] ?? '';
            $this->orderPickup->patient_name = $jsonData['patient_name'] ?? '';

            if ($this->orderPickup->save()) {

                $myZone = $this->zoneMember->where('member_id', $memberLogin->member_id)->first();

                $sales = $this->zoneMember
                    ->leftJoin('members', 'zone_members.member_id', '=', 'members.id')
                    ->where('zone_members.zone_id', $myZone->zone_id)
                    ->where('members.role', 3)
                    ->get();

                foreach ($sales ?? [] as $sale) {
                    foreach ($sale->logins ?? [] as $login) {
                        $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                            'title' => $memberLogin->member->name ?? " Pickup Notification",
                            'message' => 'มีการเรียกรับงานใหม่ กรุณาเข้าแอปเพื่อดูรายละเอียด'
                        ]);
                    }
                }
                
                $this->sendNotificationToDevice($memberLogin->device_type, $memberLogin->device_token, [
                    'title' => 'Pickup Notification',
                    'message' => 'ได้รับคำสั่งเรียกรับงานแล้ว อยู่ระหว่างดำเนินการครับ'
                ]);

                $response = flash_message('ได้รับคำสั่งเรียกรับงานแล้ว อยู่ระหว่างดำเนินการครับ', true);
            }

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getCurrentPickups()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $memberIds = [];

            $myZoneIds = $this->zoneMember
                ->where('member_id', $memberLogin->member_id)
                ->groupBy('zone_id')
                ->get()
                ->pluck('zone_id');

            $memberIds = [];

            $memberIds = $this->zoneMember
                ->whereIn('zone_id', $myZoneIds)
                ->groupBy('member_id')
                ->get()
                ->pluck('member_id');

            $orderPickups = $this->orderPickup
                ->whereIn('member_id', $memberIds)
                ->where([
                    ['checked', 0]
                ])
                ->orderBy('updated_at', 'desc')
                ->limit(200)
                ->get();
            
            $response = flash_message('success', true);
            $response['order_pickups'] = map_response_pickups($orderPickups);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function searchOrderPickups()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $param = $this->request->all();

            $memberIds = [];

            $myZoneIds = $this->zoneMember
                ->where('member_id', $memberLogin->member_id)
                ->groupBy('zone_id')
                ->get()
                ->pluck('zone_id');

            $memberIds = [];

            $memberIds = $this->zoneMember
                ->whereIn('zone_id', $myZoneIds)
                ->groupBy('member_id')
                ->get()
                ->pluck('member_id');

            $findOrderPickups = $this->orderPickup
            ->whereIn('member_id', $memberIds)
            ->where([
                ['checked', 0]
            ])
            ->where(function($query) use ($param) {

                if ($param['key'] != '') {
                    $query->orWhere('patient_name', 'like', '%'. $param['key'] .'%');
                    $query->orWhere('doctor_name', 'like', '%'. $param['key'] .'%');
                    $query->orWhere('address', 'like', '%'. $param['key'] .'%');
                }
            })
            ->orderBy('updated_at', 'desc');

            $orderPickups = $findOrderPickups->limit(200)->get();
            
            if (!$orderPickups->count()) {

                $response['message'] = "Not found.";
                return response()->json($response);
            }

            $response = flash_message('success', true);
            $response['order_pickups'] = map_response_pickups($orderPickups);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getPopupCheckIn()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $param = $this->request->all();

            $validator = Validator::make($param, [
                'order_pickup_id' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $orderPickup = $this->orderPickup->find($param['order_pickup_id']);

            if (!$orderPickup) {

                $response['message'] = "Order pickup data not found.";
                return response()->json($response);
            }

            $response = flash_message('Get order pickup success.', true);
            $response['data'] = [
                'id' => $orderPickup->id,
                'time' => set_date_format2($orderPickup->created_at).' '.$orderPickup->time,
                'clinic' => $orderPickup->member->name,
                'address' => $orderPickup->member->address
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function confirmPickup()
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
                'id' => 'required',
                'lat' => 'required',
                'long' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $orderPickup = $this->orderPickup->find($jsonData['id']);
            $orderPickup->checked = 1;
            $orderPickup->sale_id = $memberLogin->member_id;
            $orderPickup->lat = $jsonData['lat'];
            $orderPickup->long = $jsonData['long'];

            if ($orderPickup->save()) {

                $members = $this->member->where('id', $orderPickup->member_id)->get();
                foreach ($members ?? [] as $member) {
                    foreach ($member->logins ?? [] as $login) {
                        $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                            'title' => 'Pickup Notification',
                            'message' => 'ได้รับคำสั่งเรียกรับงานแล้วอยู่ระหว่างการดำเนินการ'
                        ]);
                    }
                }

                $response = flash_message('กดรับงานของลูกค้าเสร็จเรียบร้อย', true);
            }

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function checkIn()
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
                'lat' => 'required',
                'long' => 'required',
                'time' => 'required',
                'location' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $this->orderPickup->member_id = $memberLogin->member_id;
            $this->orderPickup->address = $jsonData['location'];
            $this->orderPickup->time = $jsonData['time'];
            $this->orderPickup->lat = $jsonData['lat'];
            $this->orderPickup->long = $jsonData['long'];
            $this->orderPickup->checked = 1;

            if ($this->orderPickup->save()) {

                $response = flash_message('success', true);
            }

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function checkInHistory()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $orderPickups = $this->orderPickup
                ->where([
                    ['checked', 1],
                    ['member_id', $memberLogin->member_id],
                    ['branch_id', 0]
                ])
                ->orderBy('id', 'desc')
                ->get();
            
            $response = flash_message('success', true);
            $response['check_ins'] = map_response_check_ins($orderPickups);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function removeOrderPickup()
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
                'order_pickup_id' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $orderPickup = $this->orderPickup->where([
                ['id', $jsonData['order_pickup_id']]
            ])->first();
            
            if (!$orderPickup) {

                $response['message'] = "Can not remove your order pickup.";
                return response()->json($response);
            }

            if ($orderPickup->delete()) {
                
                $response = flash_message('success', true);
                return response()->json($response);
            }

            $response['message'] = 'Can not remove your order pickup.';
            return response()->json($response);
        }
    }
}

