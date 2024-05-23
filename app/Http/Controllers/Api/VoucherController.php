<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Voucher;
use App\Member;
use App\MemberLogin;
use App\Promotion;
use App\Redemption;
use App\VoucherCart;

use DB;

use Validator;

use App\Http\Controllers\Api\MemberController as MemberController;

class VoucherController extends Controller
{
    public $request;
    public $voucher;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $promotion;
    public $redemption;
    public $voucherCart;

    public $memberController;

    public function __construct(
        Voucher $voucher,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Promotion $promotion,
        Redemption $redemption,
        MemberController $memberController,
        VoucherCart $voucherCart
    ) {
        $this->voucher = $voucher;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->promotion = $promotion;
        $this->redemption = $redemption;
        $this->memberController = $memberController;
        $this->voucherCart = $voucherCart;
    }

    /**
     * Get Voucher Detail
     * 
     */
    public function getVoucherDetail()
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
                'voucher_id' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $voucher = $this->voucher->find($param['voucher_id']);

            if (!$voucher) {

                $response['message'] = "Voucher data not found.";
                return response()->json($response);
            }

            $memberPoint = ($memberLogin->member->type == 'doctor') ? $this->memberController->getDoctorPoint($memberLogin->member->customer_verify_key) : 0;
            $usedTotalPointRedemption = $memberLogin->member->totalPointRedeemed();
            $usedTotalPointVoucherCart = $memberLogin->member->totalPointVoucherCart();

            if ($memberPoint < ($usedTotalPointRedemption + $usedTotalPointVoucherCart)) {

                $response['message'] = "ขออภัย คะแนนสะสมไม่เพียงพอ";
                return response()->json($response);

            }

            $response = flash_message('success', true);

            $choices = [];
            foreach ($voucher->options ?? [] as $option) {

                $choices[$option->redeem_point] = 'แลกของรางวัลมูลค่า '. @number_format($option->redeem_point) .' บาท ใช้ '. @number_format($option->redeem_point * $voucher->exchange_rate) .' คะแนน';
            }

            $usePointTotal = ($usedTotalPointRedemption + $usedTotalPointVoucherCart);

            $response['data'] = [
                'id' => $voucher->id,
                'image' => store_image($voucher->image),
                'name' => $voucher->name,
                'exchange_rate' => $voucher->exchange_rate,
                'voucher_value' => $voucher->voucher_value,
                'voucher_condition' => $voucher->voucher_condition,
                'description' => $voucher->description,
                'choices' => $choices,
                'reward_point_total' => ($memberLogin->member->type == 'doctor') ? ($this->memberController->getDoctorPoint($memberLogin->member->customer_verify_key) - $usePointTotal) : 0
            ];

            return response()->json($response);
        }
    }

    /**
     * Add Reward List To Cart
     * 
     */
    public function addVoucherToCart()
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
                'voucher_id' => 'required',
                'point' => 'required|numeric'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            if (empty($jsonData['point'])) {

                $response = [
                    'status' => false,
                    'message' => "กรอกคะแนนอย่างน้อย 1 คะแนนขึ้นไป",
                ];

                return response()->json($response);
            }

            $memberPoint = ($memberLogin->member->type == 'doctor') ? $this->memberController->getDoctorPoint($memberLogin->member->customer_verify_key) : 0;
            $usedTotalPointRedemption = $memberLogin->member->totalPointRedeemed();
            $usedTotalPointVoucherCart = $memberLogin->member->totalPointVoucherCart();
            $usePointTotal = ($usedTotalPointRedemption + $usedTotalPointVoucherCart) + $jsonData['point'];

            if ($memberPoint < $usePointTotal) {

                $response['message'] = "ขออภัย คะแนนสะสมไม่เพียงพอ";
                return response()->json($response);

            }

            $voucher = $this->voucher->find($jsonData['voucher_id']);

            $this->voucherCart->member_id = $memberLogin->member_id;
            $this->voucherCart->voucher_id = $jsonData['voucher_id'];
            $this->voucherCart->point = $jsonData['point'];
            $this->voucherCart->voucher_value = ($jsonData['point'] / $voucher->exchange_rate);

            if ($this->voucherCart->save()) {

                $response = [
                    'status' => true,
                    'message' => "เพิ่มของรางวัลลงในตะกร้าเรียบร้อยแล้ว",
                    'data' => [
                        'id' => $this->voucherCart->id,
                        'member_id' => $this->voucherCart->member_id,
                        'voucher_id' => $this->voucherCart->voucher_id,
                        'point' => $this->voucherCart->point,
                        'voucher_value' => $this->voucherCart->voucher_value
                    ]
                ];
            }

            return response()->json($response);
        }
    }

    /**
     *  Get Voucher List My Cart
     * 
     */
    public function getVoucherMyCart()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $voucherCarts = $this->voucherCart->where('member_id', $memberLogin->member_id)->get();
            $redemption = $this->redemption->where('member_id', $memberLogin->member_id)->latest('created_at')->first();

            $memberData = [];

            if ($redemption) {
                
                $memberData = [
                    'name' => (!$redemption->name) ? $memberLogin->member->name : $redemption->name,
                    'address' => $redemption->address,
                    'phone' => $redemption->phone
                ];
            }

            $usedTotalPointRedemption = $memberLogin->member->totalPointRedeemed();
            $usedTotalPointVoucherCart = $memberLogin->member->totalPointVoucherCart();
            $usePointTotal = ($usedTotalPointRedemption + $usedTotalPointVoucherCart);

            $response = flash_message('get voucher cart success.', true);
            $response['data']['member_address'] = $memberData;
            $response['data']['voucher_cart'] = map_voucher_cart_response($voucherCarts);
            $response['data']['reward_point_total'] = ($memberLogin->member->type == 'doctor') ? ($this->memberController->getDoctorPoint($memberLogin->member->customer_verify_key) - $usePointTotal) : 0;

            return response()->json($response);
        }
    }

     /**
     *  Remove Reward List In Cart
     * 
     */
    public function removeVoucherList()
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
                'voucher_cart_id' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            foreach ($jsonData['voucher_cart_id'] ?? [] as $key => $value) {
                
                $checkVoucher = $this->voucherCart->find($value);
                if (!$checkVoucher) {
    
                    $response['message'] = "Can not remove your voucher.";
                    return response()->json($response);
                }

                $this->voucherCart->where('id', $value)->delete();
                $response = flash_message('ลบรายการของรางวัลที่แลก เสร็จเรียบร้อย', true);
            }

            return response()->json($response);
        }
    }

    /**
     * Check Member Address In Redemptions Table
     * 
     */
    public function getMemberAddressRedemption()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $redemption = $this->redemption->where('member_id', $memberLogin->member_id)->latest('created_at')->first();

            if (!$redemption) {
                $response = [
                    'status' => false,
                    'message' => "Not found",
                    'data' => []
                ];
                
                return response()->json($response);

            } else {

                $response = [
                    'status' => true,
                    'message' => "Success",
                    'data' => [
                        'member_id' => $redemption->member_id,
                        'name' => (!$redemption->name) ? $memberLogin->member->name : $redemption->name,
                        'address' => $redemption->address,
                        'phone' => $redemption->phone
                    ]
                ];
                
                return response()->json($response);
            }
        }
    }

    /**
     * Confirm Reward Exchange
     * 
     */
    public function redeem()
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
                'voucher_id' => 'required',
                'point' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $memberPoint = ($memberLogin->member->type == 'doctor') ? $this->memberController->getDoctorPoint($memberLogin->member->customer_verify_key) : 0;
            $usedTotalPointRedemption = $memberLogin->member->totalPointRedeemed();
            $usedTotalPoint = $jsonData['point'];

            if ($memberPoint < ($usedTotalPointRedemption + $usedTotalPoint)) {

                $response['message'] = "ขออภัย คะแนนสะสมไม่เพียงพอ";
                return response()->json($response);

            }

            $voucher = $this->voucher->find($jsonData['voucher_id']);

            $this->redemption->member_id = $memberLogin->member_id;
            $this->redemption->voucher_id = $jsonData['voucher_id'];
            $this->redemption->code = str_pad(($jsonData['voucher_id'] * $memberLogin->member_id) . time(), 10, '0', STR_PAD_LEFT);
            $this->redemption->point = $jsonData['point'];
            $this->redemption->amount = ($jsonData['point'] / $voucher->exchange_rate);
            $this->redemption->approved = 0;
            $this->redemption->use_status = 0;
            $this->redemption->name = (!$jsonData['name']) ? $memberLogin->member->name : $jsonData['name'];
            $this->redemption->clinic = $jsonData['clinic'];
            $this->redemption->address = $jsonData['address'];
            $this->redemption->phone = $jsonData['phone'];
            $this->redemption->created_at = date('Y-m-d H:i:s');
            $this->redemption->updated_at = date('Y-m-d H:i:s');

            if($this->redemption->save()) {

                $marketings = $this->member->where('role', 4)->get();
                foreach ($marketings ?? [] as $marketing) {
                    foreach ($marketing->logins ?? [] as $login) {
                        $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                            'title' => 'Hexa Reward Notification',
                            'message' => 'มีการแลกคะแนนใหม่'
                        ]);
                    }
                }

                $response = flash_message('บริษัท ฯ จะจัดส่งของรางวัลภายใน 10 วันทำการ', true);
            }

            return response()->json($response);
        }
    }

    /**
     * Confirm Reward in Cart Exchange 
     * 
     */
    public function redeemVoucherCart()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $jsonData = $this->request->json()->all();

            $jsonData['address_type'] = $jsonData['address_type'] ?? 'new';

            $validator = Validator::make($jsonData, [
                'voucher_cart_id' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            DB::beginTransaction();

            $voucherMyCart = $this->voucherCart->where('id', $memberLogin->member_id)->get(); //get voucher in voucher_carts table

            $voucherList = [];
            foreach ($jsonData['voucher_cart_id'] ?? [] as $key => $value) {
                $voucherMyCart = $this->voucherCart->find($value);
                $voucherList[] = [
                    'voucher_id' => $voucherMyCart['voucher_id'],
                    'member_id' => $memberLogin->member_id,
                    'code' => str_pad(($voucherMyCart['voucher_id'] * $memberLogin->member_id) . time(), 10, '0', STR_PAD_LEFT),
                    'point' => $voucherMyCart['point'],
                    'amount' => $voucherMyCart['voucher_value'],
                    'approved' => 0,
                    'use_status' => 0,
                    'name' => (!$jsonData['name']) ? $memberLogin->member->name : $jsonData['name'],
                    'clinic' => $jsonData['clinic'],
                    'address' => $jsonData['address'],
                    'phone' => $jsonData['phone'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }

            if($this->redemption->insert($voucherList)) {

                //delete voucher list where member_id in voucher_carts table
                foreach ($jsonData['voucher_cart_id'] ?? [] as $key => $value) {
                    $this->voucherCart->where('id', $value)->delete();
                }

                $marketings = $this->member->where('role', 4)->get();
                foreach ($marketings ?? [] as $marketing) {
                    foreach ($marketing->logins ?? [] as $login) {
                        $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                            'title' => 'Hexa Reward Notification',
                            'message' => 'มีการแลกคะแนนใหม่'
                        ]);
                    }
                }

                $response = flash_message('บริษัท ฯ จะจัดส่งของรางวัลภายใน 10 วันทำการ', true);
            }

            DB::commit();

            return response()->json($response);
        }
    }
}