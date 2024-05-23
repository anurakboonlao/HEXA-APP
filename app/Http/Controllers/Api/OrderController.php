<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Setting;
use App\Member;
use App\MemberLogin;
use App\Cart;
use App\Product;
use App\ProductDiscount;

use App\Order;
use App\OrderProduct;

use DB;

use Validator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class OrderController extends Controller
{
    public $request;
    public $setting;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $cart;
    public $product;
    public $productDiscount;
    public $order;
    public $orderProduct;
    public $httpRequest;

    public function __construct(
        Setting $setting,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Cart $cart,
        Product $product,
        ProductDiscount $productDiscount,
        Order $order,
        OrderProduct $orderProduct
    ) {
        $this->setting = $setting;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->cart = $cart;
        $this->product = $product;
        $this->productDiscount = $productDiscount;
        $this->order = $order;
        $this->orderProduct = $orderProduct;
        $this->httpRequest = new Client();
    }

    /**
     * 
     * 
     */
    public function getMyOrders($status = 'current')
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $orders = $this->order
                ->where([
                    ['member_id', $memberLogin->member_id],
                    ['status', ($status == 'current') ? 0 : 1]
                ])
                ->orderBy('id', 'desc')
                ->get();
            
            $response = flash_message('success', true);
            $response['data']['orders'] = map_response_orders($orders);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getOrderDetail()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $jsonData = $this->request->json()->all();
            
            $validator = Validator::make($jsonData, [
                'order_id' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $order = $this->order->find($jsonData['order_id']);

            $response = flash_message('success', true);
            $response['data'] = map_response_order($order);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function confirmOrder()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $jsonData = $this->request->json()->all();
            
            $validator = Validator::make($jsonData, [
                'order_id' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $order = $this->order->find($jsonData['order_id']);
            $order->status = 1;
            $order->save();

            $response = flash_message('success', true);
            $response['data']['order'] = map_response_order($order);

            $members = $this->member->where('id', $order->member_id)->get();
            foreach ($members ?? [] as $member) {
                foreach ($member->logins ?? [] as $login) {
                    $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                        'title' => 'Shopping Notification',
                        'message' => 'คำสั่งซื้อของลูกค้าได้รับการยืนยันแล้ว'
                    ]);
                }
            }

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getIncomingOrder()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $orders = $this->order
                ->where([
                    //['member_id', $memberLogin->member_id],
                    ['status', 0]
                ])
                ->orderBy('id', 'desc')
                ->get();
            
            $response = flash_message('success', true);
            $response['data']['orders'] = map_response_orders($orders, 'long');

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getConfirmOrder()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            if (!$memberLogin) {
                
                return response()->json($response, 401);
            }

            $orders = $this->order
                ->where([
                    //['member_id', $memberLogin->member_id],
                    ['status', 1]
                ])
                ->orderBy('id', 'desc')
                ->get();
            
            $response = flash_message('success', true);
            $response['data']['orders'] = map_response_orders($orders, 'long');

            return response()->json($response);
        }
    }
}
