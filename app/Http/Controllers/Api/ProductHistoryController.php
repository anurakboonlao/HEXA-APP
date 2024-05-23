<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Member;
use App\MemberLogin;
use App\ProductHistory;

use DB;

use Validator;

class ProductHistoryController extends Controller
{
    public $request;
    public $product;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $productHistory;

    public function __construct(
        Product $product,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        ProductHistory $productHistory
    ) {
        $this->product = $product;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->productHistory = $productHistory;
    }

    /**
     * 
     * 
     */
    public function getProductHistories()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response, 401);
            }

            $productHistories = $this->productHistory
                ->where('member_id', $memberLogin->member_id)
                ->groupBy('product_id')
                ->orderBy('id', 'desc')
                ->limit(30)
                ->get();

            $response = flash_message('success', true);
            $response['data']['histories'] = map_data_product_history($productHistories);

            return response()->json($response);
        }
    }
}