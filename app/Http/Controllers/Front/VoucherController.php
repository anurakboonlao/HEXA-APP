<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Voucher;
use App\Member;
use App\MemberLogin;
use App\Redemption;
use App\VoucherCart;
use App\Setting;
use App\Promotion;
use App\VoucherOption;

use DB;

use Validator;

class VoucherController extends Controller
{
    public $request;
    public $voucher;
    public $voucherOption;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $redemption;
    public $voucherCart;
    public $setting;
    public $promotion;

    public $memberController;

    public function __construct(
        Voucher $voucher,
        VoucherOption $voucherOption,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Redemption $redemption,
        MemberController $memberController,
        VoucherCart $voucherCart,
        Setting $setting,
        Promotion $promotion
    ) {
        $this->voucher = $voucher;
        $this->voucherOption = $voucherOption;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->redemption = $redemption;
        $this->memberController = $memberController;
        $this->voucherCart = $voucherCart;
        $this->setting = $setting;
        $this->promotion = $promotion;
    }

    public function home()
    {
        $response = [];

        $response['setting'] = $this->setting->first();
        $response['promotions'] = $this->promotion
            ->where([
                ['public', 1],
                ['type', 2]
            ])
            ->orderBy('sort', 'asc')
            ->get();

        $response['vouchers'] = $this->voucher
            ->where('public', 1)
            ->orderBy('id', 'desc')
            ->get();

        return view('front.reward.reward', $response);

    }

    /**
     * Get Voucher Detail
     * 
     */
    public function getVoucherDetail(Request $request, $id)
    {
        $response = flash_message('ขออภัย คะแนนสะสมไม่เพียงพอ');

        $member = $this->member->find($request->input('member_id'));
        $voucher = $this->voucher->find($id);
        $voucherOptionLimit = $this->voucherOption->where('voucher_id', $voucher->id)->orderBy('redeem_point', 'asc')->first();

        $memberPoint = ($member->type == 'doctor') ? $this->memberController->getDoctorPoint($member->customer_verify_key) : 0;
        $usedTotalPointRedemption = $member->totalPointRedeemed();
        $usedTotalPointVoucherCart = $member->totalPointVoucherCart();

        if ($memberPoint < ($usedTotalPointRedemption + $usedTotalPointVoucherCart)) {

            return response()->json($response);

        }

        $response = flash_message('success', true);

        $choices = [];
        foreach ($voucher->options ?? [] as $option) {

            $choices[$option->redeem_point] = 'แลกของรางวัลมูลค่า '. @number_format($option->redeem_point) .' บาท ใช้ '. @number_format($option->redeem_point * $voucher->exchange_rate) .' คะแนน';
        }

        $usePointTotal = ($usedTotalPointRedemption + $usedTotalPointVoucherCart);

        $response['voucher'] = [
            'id' => $voucher->id,
            'image' => store_image($voucher->image),
            'name' => $voucher->name,
            'exchange_rate' => $voucher->exchange_rate,
            'voucher_value' => $voucher->voucher_value,
            'voucher_option_limit' => $voucherOptionLimit->redeem_point,
            'voucher_condition' => $voucher->voucher_condition,
            'description' => $voucher->description,
            'choices' => $choices,
            'reward_point_total' => ($member->type == 'doctor') ? ($this->memberController->getDoctorPoint($member->customer_verify_key) - $usePointTotal) : 0
        ];

        return view('front.reward.get_reward_detail', $response);
    }

    /**
     * 
     * 
     */
    public function getRewardHistory(Request $request, $id)
    {
        $response = [
            'status' => false,
            'message' => "Error",
        ];

        $memberLogin = $this->member->find($id);

        $status = $this->request->input('status') ?? 'approved';

        $vouchers = $this->redemption
            ->where('member_id', $id)
            ->orderBy('approved', 'asc')
            ->get();

        $response = [
            'status' => true,
            'message' => "Success",
        ];

        //$response['redemptions'] = map_member_vouchers($rewardHistory-get());
        $response['redemptions'] = map_member_vouchers($vouchers);

        return view('front.reward.get_reward_history', $response);
    }

    /**
     * Add Reward List To Cart
     * 
     */
    public function addVoucherToCart(Request $request)
    {
        $response = [
            'status' => false,
            'message' => "มีบางอย่างผิดพลาด"
        ];

        $params = $this->request->all();

        $validator = Validator::make($params, [
            'voucher_id' => 'required',
            'amount' => 'required|numeric'
        ]);
    
        if ($validator->fails()) {

            $response['message'] = $validator->errors()->first();
            return response()->json($response);
        }

        $voucher = $this->voucher->find($params['voucher_id']);

        $this->voucherCart->member_id = $params['member_id'];
        $this->voucherCart->voucher_id = $params['voucher_id'];
        $this->voucherCart->point = ($params['amount'] * $voucher->exchange_rate);
        $this->voucherCart->voucher_value = $params['amount'];

        if ($this->voucherCart->save()) {

            $response = [
                'status' => true,
                'message' => "เพิ่มของรางวัลลงในตะกร้าเรียบร้อยแล้ว"
            ];
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Remove Voucher list
     * 
     */
    public function removeVoucherList($id) 
    {
        $response = flash_message('Can not remove your voucher.');

        $checkVoucher = $this->voucherCart->find($id);

        if (!$checkVoucher) {
    
            return response()->json($response);
        }

        $this->voucherCart->where('id', $id)->delete();

        $response = flash_message('ลบรายการของรางวัลที่แลก เสร็จเรียบร้อย', true);

        return response()->json($response);
    }

    /**
     * Get Voucher Cart & Erp Doctor Address Contact All 
     * 
     */
    public function getVoucherCart(Request $request)
    {
        $response = [];

        $member = $this->member->find($request->input('member_id'));

        $voucherCarts = $this->voucherCart->where('member_id', $member->id)->get();
        $redemption = $this->redemption->where('member_id', $member->id)->latest('created_at')->first();

        $usedTotalPointRedemption = $member->totalPointRedeemed();
        $usedTotalPointVoucherCart = $member->totalPointVoucherCart();
        $usePointTotal = ($usedTotalPointRedemption + $usedTotalPointVoucherCart);

        $response = flash_message('get voucher cart success.', true);
        $response['voucher_carts'] = map_voucher_cart_response($voucherCarts);
        $response['reward_point_total'] = ($member->type == 'doctor') ? ($this->memberController->getDoctorPoint($member->customer_verify_key) - $usedTotalPointRedemption) : 0;
        $response['use_point_total'] = ($member->type == 'doctor') ? ($this->memberController->getDoctorPoint($member->customer_verify_key) - $usePointTotal) : 0;

        //return response()->json($response);
        return view('front.reward.get_reward_cart', $response);
    }

    /**
     * Erp Doctor Address Contact All 
     * 
     */
    public function getDoctorAddress(Request $request)
    {
        $response = [];

        $member = $this->member->find($request->input('member_id'));

        $doctorContacts = $this->memberController->doctorGetContactErp($member->id);

        $response = flash_message('get doctor contacts.', true);
        $response['doctor_contacts'] = $doctorContacts;

        return view('front.reward.get_doctor_contact', $response);
    } 

     /**
     * Redemption reward ( 1 more )
     * 
     */
    public function redeem(Request $request)
    {
        $response = flash_message('The authentication has failed please login again.');

        $params = $this->request->all();
        $member = $this->member->find($this->request->member->id);

        $validator = Validator::make($params, [
            'voucher_id' => 'required',
            'point' => 'required'
        ]);
    
        if ($validator->fails()) {

            $response['message'] = $validator->errors()->first();
            return redurect()->back()->with('message', $response);
        }

        if ($request->has('doctor_contact_ids')) {

            $erpContact = $this->memberController->findContactErp($params['doctor_contact_ids'], 'id');
        }

        $voucher = $this->voucher->find($params['voucher_id']);

        $this->redemption->member_id = $member->id;
        $this->redemption->voucher_id = $params['voucher_id'];
        $this->redemption->code = str_pad(($params['voucher_id'] * $member->id) . time(), 10, '0', STR_PAD_LEFT);
        $this->redemption->point = $params['point'];
        $this->redemption->amount = ($params['point'] / $voucher->exchange_rate);
        $this->redemption->approved = 0;
        $this->redemption->use_status = 0;
        $this->redemption->name = (!$params['name']) ? $member->name : $params['name'];
        $this->redemption->clinic = (!$params['clinic']) ? $erpContact['name'] : $params['clinic'];
        $this->redemption->address = (!$params['address']) ? $erpContact['address'] : $params['address'];
        $this->redemption->phone = (!$params['phone']) ? $erpContact['phone'] : $params['phone'];
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
            //$response = flash_message('บริษัท ฯ จะจัดส่งของรางวัลภายใน 10 วันทำการ', true);
        }

        return redirect()->back()->with('modal', 4);
    }

    /**
     * Redemption reward cart ( multiple item )
     * 
     */
    public function redeemVoucherCart(Request $request)
    {
        $response = flash_message('มีบางอย่างผิดพลาด กรุณาลองใหม่');

        $params = $request->all();

        $validator = Validator::make($params, [
            'voucher_cart_id' => 'required'
        ]);
    
        if ($validator->fails()) {

            $response['message'] = $validator->errors()->first();
            return redirect()->back()->with('message', $response);
        }

        DB::beginTransaction();

        $member = $this->member->find($this->request->member->id);

        $voucherMyCart = $this->voucherCart->where('id', $params['voucher_cart_id'])->get(); //get voucher in voucher_carts table

        if ($request->has('doctor_contact_ids')) {

            $erpContact = $this->memberController->findContactErp($params['doctor_contact_ids'], 'id');
        }

        $voucherList = [];
        foreach ($params['voucher_cart_id'] ?? [] as $key => $value) {
            $voucherMyCart = $this->voucherCart->find($value);
            $voucherList[] = [
                'voucher_id' => $voucherMyCart['voucher_id'],
                'member_id' => $member->id,
                'code' => str_pad(($voucherMyCart['voucher_id'] * $member->id) . time(), 10, '0', STR_PAD_LEFT),
                'point' => $voucherMyCart['point'],
                'amount' => $voucherMyCart['voucher_value'],
                'approved' => 0,
                'use_status' => 0,
                'name' => (!$params['name']) ? $member->name : $params['name'],
                'clinic' => (!$params['clinic']) ? $erpContact['name'] : $params['clinic'],
                'address' => (!$params['address']) ? $erpContact['address'] : $params['address'],
                'phone' => (!$params['phone']) ? $erpContact['phone'] : $params['phone'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        if($this->redemption->insert($voucherList)) {

            //delete voucher list where member_id in voucher_carts table
            foreach ($params['voucher_cart_id'] ?? [] as $key => $value) {
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

            //$response = flash_message('บริษัท ฯ จะจัดส่งของรางวัลภายใน 10 วันทำการ', true);
        }

        DB::commit();

        return redirect()->back()->with('modal', 4);
    }

    /**
     * Get Reward To Modal
     * 
     */
    public function getRewardToModal(Request $request)
    {
        $response = [];

        $member = $this->member->find($request->input('member_id'));

        $usedTotalPointRedemption = $member->totalPointRedeemed();

        $response['reward_data'] = $request->all();
        $response['reward_point'] = ($member->type == 'doctor') ? ($this->memberController->getDoctorPoint($member->customer_verify_key) - $usedTotalPointRedemption) : 0;

        return view('front.reward.pass_reward_to_modal', $response);
    }
    
}
