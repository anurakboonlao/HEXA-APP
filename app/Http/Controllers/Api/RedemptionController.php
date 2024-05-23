<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Voucher;
use App\Member;
use App\MemberLogin;
use App\Promotion;
use App\Redemption;

use DB;

use Validator;

class RedemptionController extends Controller
{
    public $request;
    public $voucher;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $promotion;
    public $redemption;

    public function __construct(
        Voucher $voucher,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Promotion $promotion,
        Redemption $redemption
    ) {
        $this->voucher = $voucher;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->promotion = $promotion;
        $this->redemption = $redemption;
    }

    /**
     * 
     * 
     */
    public function index()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $jsonData = $this->request->json()->all();

            $status = $this->request->input('status') ?? 'approved';
            $vouchers = $this->redemption
                ->where([
                    ['member_id', $memberLogin->member_id],
                    ['approved', ($status == 'approved') ? 1 : 0]
                ])
                ->orderBy('id', 'desc')
                ->get();

            $response = flash_message('success', true);

            $response['data']['redemptions'] = map_member_vouchers($vouchers);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function show($id)
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $jsonData = $this->request->json()->all();
            $redemption = $this->redemption->find($id);
            if (!$redemption) {

                $response['message'] = "Voucher data not found.";
                return response()->json($response);
            }

            $response = flash_message('success', true);
            $response['data']['redemption'] = [
                'id' => $redemption->id,
                'point' => $redemption->point,
                'amount' => $redemption->amount,
                'code' => $redemption->id,
                'approved' => $redemption->approved,
                'date' => set_date_format($redemption->created_at),
                'voucher_total' => "1 * ".$redemption->amount." THB",
                'voucher' => [
                    'id' => $redemption->voucher->id,
                    'image' => store_image($redemption->voucher->image),
                    'name' => $redemption->voucher->name,
                    'exchange_rate' => $redemption->voucher->exchange_rate,
                    'voucher_value' => $redemption->voucher->voucher_value,
                    'description' => $redemption->voucher->description
                ]
            ];

            return response()->json($response);
        }
    }
}
