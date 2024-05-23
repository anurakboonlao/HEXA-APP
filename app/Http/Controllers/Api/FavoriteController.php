<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Product;
use App\Member;
use App\MemberLogin;
use App\Favorite;

use DB;

use Validator;

class FavoriteController extends Controller
{
    public $request;
    public $product;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $favorite;

    public function __construct(
        Product $product,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Favorite $favorite
    ) {
        $this->product = $product;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->favorite = $favorite;
    }

    /**
     * 
     * 
     */
    public function getFavorites()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');

            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {

                return response()->json($response ,401);
            }

            $favorites = $this->favorite->where('member_id', $memberLogin->member_id)->orderBy('id', 'desc')->get();

            $response = flash_message('success', true);
            $response['data']['favorites'] = map_data_favorite($favorites);

            return response()->json($response);
        }
    }
    
    /**
     * 
     * 
     */
    public function addToFavorite()
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

            $favoriteData = $this->favorite->where([
                ['product_id', $jsonData['product_id']],
                ['member_id', $memberLogin->member_id]
            ])->first();

            $favoriteObj = ($favoriteData) ? $favoriteData : $this->favorite; 

            $favoriteObj->product_id = $jsonData['product_id'];
            $favoriteObj->member_id = $memberLogin->member_id;

            if ($favoriteObj->save()) {
                
                $response = flash_message('success', true);
                return response()->json($response);
            }

            $response['message'] = 'Can not add the product to your favorite.';
            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function removeFavorite()
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

            $favorite = $this->favorite->where([
                ['product_id', $jsonData['product_id']],
                ['member_id', $memberLogin->member_id]
            ])->first();
            
            if (!$favorite) {

                $response['message'] = "Can not remove your favorite.";
                return response()->json($response);
            }

            if ($favorite->delete()) {
                
                $response = flash_message('success', true);
                return response()->json($response);
            }

            $response['message'] = 'Can not remove your favorite.';
            return response()->json($response);
        }
    }
}