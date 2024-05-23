<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Setting;
use App\Member;
use App\MemberLogin;
use App\Promotion;
use App\Product;
use App\Voucher;
use App\Bank;
use App\VoucherBanner;

use DB;

use Validator;

use App\Http\Controllers\Api\MemberController as MemberController;

class SiteController extends Controller
{
    public $request;
    public $setting;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $promotion;
    public $product;
    public $voucher;
    public $bank;
    public $memberController;
    public $voucherBanner;

    public $erpUrl;
    public $erpDB;
    public $erpUsername;
    public $erpPassword;

    public function __construct(
        Setting $setting,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Promotion $promotion,
        Product $product,
        Voucher $voucher,
        Bank $bank,
        MemberController $memberController,
        VoucherBanner $voucherBanner
    ) {
        $this->setting = $setting;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->promotion = $promotion;
        $this->product = $product;
        $this->voucher = $voucher;
        $this->bank = $bank;
        $this->memberController = $memberController;
        $this->voucherBanner = $voucherBanner;

        $this->erpUrl = env('ERP_HOST', 'https://hexa-marketing.netforce.co.th/xmlrpc');
        $this->erpDB = env('ERP_DATABASE', 'apptest');
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
        $response = flash_message('success', true);

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                $response = flash_message('The authentication has failed please login again.');
                return response()->json($response);
            }

            $setting = $this->setting->find(1);

            $banks = [];
            $getBanks = $this->bank
                ->where('status', 1)
                ->orderBy('name', 'asc')->get();

            foreach ($getBanks as $key => $value) {
                $banks[] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'logo' => store_image($value->image)
                ];
            }

            //check permission open price list
            $memberVerify = $this->member->find($memberLogin->member_id);

            $response['data'] = [
                'hexa_price_list' => ($memberVerify->customer_verify_key != "") ? URL('/') .'/files/settings-'.  $setting->hexa_price_list : null,
                'hexa_line' => $setting->hexa_line,
                'hexa_email' => $setting->hexa_email,
                'hexa_facebook' => $setting->hexa_facebook,
                'dental_supply' => $setting->dental_supply_link,
                'terms' => $setting->terms,
                'payment' => [
                    'banks' => $banks,
                    'qrcode' => store_image($setting->qrcode)
                ]
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getProvinces()
    {
        $response = flash_message('success', true);

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $provinces = $this->remoteMysql->table('province')->orderBy('prv_name')->get();

            $response['data']['provinces'] = [];

            foreach ($provinces as $key => $value) {
                $response['data']['provinces'][] = [
                    'id' => $value->provinceid,
                    'name' => $value->prv_name
                ];
            }

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getHomeMemberData()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $db = $this->erpDB;
            $username = $this->erpUsername;
            $password = $this->erpPassword;
            $tableName = "account.bill";
            $method = "search_read";
            
            $searchConditions = [
                ['contact_id', '=', $memberLogin->member->customer_id],
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
            $promotions = $this->promotion->where([
                    ['public', 1],
                    ['type', 1]
                ])
                ->orderBy('sort', 'asc')
                ->get();

            $usedTotalPointRedemption = $memberLogin->member->totalPointRedeemed();
            $usedTotalPointVoucherCart = $memberLogin->member->totalPointVoucherCart();

            $usePointTotal = ($usedTotalPointRedemption + $usedTotalPointVoucherCart);

            $response = flash_message('success', true);
            $response['data']['summary'] = [
                'balance_due_total' => $billData['amount_total'],
                'reward_point_total' => ($memberLogin->member->type == 'doctor') ? ($this->memberController->getDoctorPoint($memberLogin->member->customer_verify_key) - $usePointTotal) : 0
            ];

            $response['data']['promotions'] = map_promotion_response($promotions);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getHomeShop()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $promotions = $this->promotion->where([
                    ['public', 1],
                    ['type', 1]
                ])
                ->orderBy('sort', 'asc')
                ->get();
            
            $products = $this->product->where([
                    ['public', 1]
                ])
                ->orderBy('recommended', 'asc')
                ->orderBy('sort', 'asc')
                ->get();
            
            $response = flash_message('success', true);
            $response['data']['promotions'] = []; //map_promotion_response($promotions);
            $response['data']['products'] = map_product_response($products, $memberLogin->member_id);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     * 
     */
    public function getHomeVoucher()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $setting = $this->setting->find(1);
            $vouchers = $this->voucher->where([
                    ['public', 1]
                ])
                ->orderBy('id', 'desc')
                ->get();

            $voucherBanners = $this->voucherBanner->where([
                    ['public', 1]
                ])
                ->orderBy('sort', 'asc')
                ->get();

            $usedTotalPointRedemption = $memberLogin->member->totalPointRedeemed();
            $usedTotalPointVoucherCart = $memberLogin->member->totalPointVoucherCart();

            $usePointTotal = ($usedTotalPointRedemption + $usedTotalPointVoucherCart);
            
            $response = flash_message('success', true);
            $response['data']['vouchers'] = map_voucher_response($vouchers);
            $response['data']['slide_banners'] = map_voucher_slide_response($voucherBanners);
            //$response['data']['cover_page'] = store_image($setting->shipping_cover_page);
            $response['data']['reward_point_total'] = ($memberLogin->member->type == 'doctor') ? ($this->memberController->getDoctorPoint($memberLogin->member->customer_verify_key) - $usePointTotal) : 0;
            
            return response()->json($response);
        }
    }

    /**
     * 
     * 
     * 
     */
    public function getPickupTimes()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $times = [
                [
                    'id' => 1,
                    'time' => '08:00-12:00',
                    'start_time' => '08:00',
                    'end_time' => '12:00',
                    'time_text' => 'Morning (08:00-12:00)'
                ],
                [
                    'id' => 2,
                    'time' => '13:00-15:00',
                    'start_time' => '13:00',
                    'end_time' => '15:00',
                    'time_text' => 'Afternoon (13:00-15:00)'
                ],
                [
                    'id' => 3,
                    'time' => '15:00-18:00',
                    'start_time' => '15:00',
                    'end_time' => '18:00',
                    'time_text' => 'Afternoon (15:00-18:00)'
                ],
                [
                    'id' => 4,
                    'time' => '00:00-18:00',
                    'start_time' => '00:00',
                    'end_time' => '18:00',
                    'time_text' => 'ด่วน (กรุณามารับด่วน)'
                ]
            ];
            
            $response = flash_message('success', true);
            $response['data'] = $times;
            
            return response()->json($response);
        }
    }
}