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

class CartController extends Controller
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
    public function getCart()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            
            if (!$memberLogin) {
                
                return response()->json($response);
            }

            $carts = $this->cart
                ->where([
                    ['member_id', $memberLogin->member_id]
                ])
                ->orderBy('id', 'desc')
                ->get();
            
            $response = flash_message('success', true);
            $response['data']['carts'] = map_response_carts($carts);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function addToCart()
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
                'qty' => 'required',
                //'size' => 'required',
                //'color' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $jsonData['color'] = $jsonData['color'] ?? '-';
            $jsonData['size'] = $jsonData['size'] ?? '-';

            $cart = $this->cart
                ->where('member_id', $memberLogin->member_id)
                ->where('product_id', $jsonData['product_id'])
                ->where('color', $jsonData['color'])
                ->where('size', $jsonData['size'])
                ->first();
            
            if ($cart) {

                $jsonData['qty'] = ($jsonData['qty'] + $cart->qty);
            }
            
            $modelCart = ($cart) ? $cart : $this->cart;
            $product = $this->product->find($jsonData['product_id']);
            $productPrice = ($this->productDiscount->discount($jsonData['product_id'], $jsonData['qty'])) ? $this->productDiscount->discount($jsonData['product_id'], $jsonData['qty']) : $product->price;

            $modelCart->member_id = $memberLogin->member_id;
            $modelCart->product_id = $jsonData['product_id'];
            $modelCart->qty = $jsonData['qty'];
            $modelCart->color = $jsonData['color'] ?? '-';
            $modelCart->size = $jsonData['size'] ?? '-';
            $modelCart->price = $productPrice;
            $modelCart->total = ($jsonData['qty'] * $productPrice);

            if ($modelCart->save()) {

                $response = flash_message('เพิ่มสินค้าในตะกร้า เรียบร้อยแล้ว', true);
            }

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function updateCart()
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
                'qty' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $cart = $this->cart->find($jsonData['id']);
            $product = $this->product->find($cart->product_id);
            $productPrice = ($this->productDiscount->discount($product->id, $jsonData['qty'])) ? $this->productDiscount->discount($product->id, $jsonData['qty']) : $product->price;

            $cart->qty = $jsonData['qty'];
            $cart->price = $productPrice;
            $cart->total = ($jsonData['qty'] * $productPrice);

            if ($cart->save()) {

                $response = flash_message('success', true);
            }

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function checkout()
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
                'address' => 'required',
                'phone' => 'required',
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            DB::beginTransaction();

            $this->order->member_id = $memberLogin->member_id;
            $this->order->email = $memberLogin->email;
            $this->order->phone = $jsonData['phone'] ?? $memberLogin->phone;
            $this->order->address = $jsonData['address'] ?? $memberLogin->address;
            $this->order->date = date('Y-m-d');
            $this->order->status = 0;
            $this->order->total = 0;

            if (!$this->order->save()) {

                $response['message'] = "can not checkout your cart.";
                return response()->json($response);
            }

            $carts = $this->cart->where('member_id', $memberLogin->member_id)->get();

            if (count($carts) < 1) {

                $response['message'] = "cart is empty.";
                return response()->json($response);
            }

            $products = [];

            foreach ($carts as $key => $cart) {
                $products[] = [
                    'order_id' => $this->order->id,
                    'product_id' => $cart->product_id,
                    'qty' => $cart->qty,
                    'price' => $cart->price,
                    'color' => $cart->color,
                    'size' => $cart->size,
                    'total' => ($cart->qty * $cart->price)
                ];
            }

            if (!$this->orderProduct->insert($products)) {

                DB::rollback();
                $response['message'] = "can not checkout your cart.";
                return response()->json($response);
            }

            $this->order->total = array_sum(array_column($products, 'total'));
            if (!$this->order->save()) {

                $response['message'] = "can not checkout your cart.";
                return response()->json($response);
            }

            $this->cart->where('member_id', $memberLogin->member_id)->delete();

            DB::commit();
            
            $response = flash_message('ขอบพระคุณสำหรับการสั่งซื้อสินค้าบริษัทฯจะจัดส่งสินค้าภายใน 2 วันทำการ', true);

            $sales = $this->member->where('role', 4)->get();
            foreach ($sales ?? [] as $sale) {
                foreach ($sale->logins ?? [] as $login) {
                    $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                        'title' => 'Shopping Notification',
                        'message' => 'มีคำสั่งซื้อสินค้าเข้ามาใหม่'
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
    public function deleteCart()
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
                'id' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $cart = $this->cart->find($jsonData['id']);
            if ($cart->delete()) {

                $response = flash_message('success', true);
            }

            return response()->json($response);
        }
    }
}
