<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Member;
use App\MemberLogin;
use App\Promotion;
use App\ProductHistory;
use App\Favorite;
use App\ProductDiscount;

use DB;

use Validator;

class ProductController extends Controller
{
    public $request;
    public $product;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $promotion;
    public $productHistory;
    public $favorite;
    public $productDiscount;

    public function __construct(
        Product $product,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Promotion $promotion,
        ProductHistory $productHistory,
        Favorite $favorite,
        ProductDiscount $productDiscount
    ) {
        $this->product = $product;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->promotion = $promotion;
        $this->productHistory = $productHistory;
        $this->favorite = $favorite;
        $this->productDiscount = $productDiscount;
    }

    /**
     * 
     * 
     */
    public function getProductDetail()
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
                'product_id' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $product = $this->product->find($jsonData['product_id']);

            if (!$product) {

                $response['message'] = "Product data not found.";
                return response()->json($response);
            }

            $historyData = [
                'product_id' => $product->id,
                'member_id' => $memberLogin->member_id,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->productHistory->insert($historyData);

            $productFavorite = $this->favorite->where([
                ['product_id', $product->id],
                ['member_id', $memberLogin->member_id]
            ])->first();

            $response = flash_message('success', true);
            $response['data']['product'] = [
                'id' => $product->id,
                'image' => store_image($product->image),
                'name' => $product->name,
                'price' => product_price_format($product->price),
                'description' => $product->description,
                'details' => $product->details,
                'sizes' => (map_json_respone(explode(',', $product->sizes))),
                'colors' => map_json_respone(explode(',', $product->colors)),
                'favorite_status' => ($productFavorite) ? true : false
            ];

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getProductPrice()
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
                'product_id' => 'required',
                'qty' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }
            
            $product = $this->product->find($jsonData['product_id']);
            $productPrice = ($this->productDiscount->discount($jsonData['product_id'], $jsonData['qty'])) ? $this->productDiscount->discount($jsonData['product_id'], $jsonData['qty']) : $product->price;

            $response = flash_message('success', true);
            $response['data']['total'] = ($jsonData['qty'] * $productPrice);

            return response()->json($response);
        }
    }
}