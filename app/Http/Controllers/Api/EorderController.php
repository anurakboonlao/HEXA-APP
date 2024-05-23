<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Setting;
use App\Member;
use App\MemberLogin;
use App\Promotion;
use App\Product;
use App\EorderComment;

use DB;

use Validator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class EorderController extends Controller
{
    public $request;
    public $setting;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $promotion;
    public $product;
    public $eorderComment;

    public $eorderUrl;
    public $httpRequest;

    public function __construct(
        Setting $setting,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Promotion $promotion,
        Product $product,
        EorderComment $eorderComment
    ) {
        $this->setting = $setting;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->promotion = $promotion;
        $this->product = $product;
        $this->eorderComment = $eorderComment;

        $this->eorderUrl = env('EORDER_URL', 'http://e-order.netforce.co.th/testeorder/eorder/api.php');
        $this->httpRequest = new Client();
    }

    /**
     * 
     * 
     */
    public function getOrders($status = 'active')
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);

            $keyword = $this->request->input('key') ?? "";
            
            if (!$memberLogin) {
                
                return response()->json($response);
            }
            
            try {

                if ($status == 'arrival') {
    
                    $comments = $this->eorderComment
                        ->where('member_id', $memberLogin->member_id)
                        ->orderBy('id', 'desc')
                        ->get();
    
                    $response = flash_message('Success', true);
                    $arrivals = [];
                    foreach ($comments ?? [] as $key => $comment) {
                        $eorder = json_decode($comment->content, true);
                        $product_type_of_work = '';
                        foreach ($eorder['product'] ?? [] as $index => $product) {

                            $product_type_of_work .= ($index > 0) ? '/' . $product['pord_name'] : $product['pord_name'];
                        }
                        
                        $arrivals[] = [
                            'id' => $eorder['e_id'],
                            'type' => $product_type_of_work,
                            'status' => $eorder['stat'],
                            'cus_name' => $eorder['cus_name'],
                            'doc_name' => $eorder['doc_name'],
                            'code' => $eorder['e_code'],
                            'patient_name' => $eorder['pat_name'],
                            'rate' => $comment->rate
                        ];
                    }
    
                    $response = flash_message('Success', true);
                    $response['data'] = $arrivals;

                    return response()->json($response);
                }

                if ($memberLogin->member->type == 'doctor') {

                    $response = flash_message('Success', true);
                    $response['data'] = [];

                    //foreach ($memberLogin->member->contacts ?? [] as $contact) {

                        $result = $this->httpRequest->request('GET', $this->eorderUrl, [
                            'query' => [
                                'filter' => 'doc',
                                'id' => $memberLogin->member->customer_verify_key,
                                'limit' => ($status == 'active') ? 10000 : 10000,
                                'stat' => ($status == 'active') ? 'A' : 'C',
                                'fine' => $keyword ?? ''
                            ]
                        ]);

                        $response['data'] = array_merge($response['data'], ($status == 'active' || $status == 'complete') ? map_eorder_response($result->getBody()->getContents(), $status) : []);
                    //}

                } else {

                    $result = $this->httpRequest->request('GET', $this->eorderUrl, [
                        'query' => [
                            'filter' => 'cus',
                            'id' => $memberLogin->member->eo_cus_id,
                            'limit' => ($status == 'active') ? 10000 : 10000,
                            'stat' => ($status == 'active') ? 'A' : 'C',
                            'fine' => $keyword ?? ''
                        ]
                    ]);
                        
                    $response = flash_message('Success', true);
                    $response['data'] = ($status == 'active' || $status == 'complete') ? map_eorder_response($result->getBody()->getContents(), $status) : [];
                }
                
            } catch (\ClientException $e) {
                
                $response = flash_message($e->getMessage());
                return response()->json($response);    
            }

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

                return response()->json($response, 401);
            }

            $param = $this->request->all();

            try {

                $result = $this->httpRequest->request('GET', $this->eorderUrl, [
                    'query' => [
                        'filter' => 'order',
                        'id' => $param['order_id']
                    ]
                ]);

                //dd(json_decode($result->getBody()->getContents(), true));
                
                $response = flash_message('Success', true);
                $response['data'] = map_eorder_detail_response($result->getBody()->getContents());
                $response['data']['comment'] = $this->eorderComment->find($param['order_id']) ?? null;
                
            } catch (\ClientException $e) {
                
                $response = flash_message($e->getMessage());
                return response()->json($response);
            }

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function addComment()
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
                'order_id' => 'required',
                'rating' => 'required'

            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            try {

                $result = $this->httpRequest->request('GET', $this->eorderUrl, [
                    'query' => [
                        'filter' => 'order',
                        'id' => $jsonData['order_id']
                    ]
                ]);

                $content = json_decode($result->getBody()->getContents(), true);
                $comment = $this->eorderComment->find($jsonData['order_id']) ?? $this->eorderComment;
                $comment->id = $jsonData['order_id'];
                $comment->rate = $jsonData['rate'] ?? $jsonData['rating'];
                $comment->message = $jsonData['message'] ?? '';
                $comment->member_id = $memberLogin->member_id;
                $comment->content = json_encode($content['data'][0]);

                if ($comment->save()) {
                    
                    $response = flash_message('ขอขอบพระคุณคุณหมอที่ให้คำแนะนำ บริษัทฯจะนำไปพัฒนาการทำงานให้ดียิ่งขึ้น', true);
                }

            } catch (\ClientException $e) {
                
                $response = flash_message($e->getMessage());
                return response()->json($response);
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

            try {

                $result = $this->httpRequest->request('GET', $this->eorderUrl, [
                    'query' => [
                        'filter' => 'order',
                        'id' => $jsonData['order_id']
                    ]
                ]);

                $content = json_decode($result->getBody()->getContents(), true);
                $comment = $this->eorderComment->find($jsonData['order_id']) ?? $this->eorderComment;
                $comment->id = $jsonData['order_id'];
                $comment->rate = 0;
                $comment->message = '';
                $comment->content = json_encode($content['data'][0]);
                $comment->member_id = $memberLogin->member_id;

                if ($comment->save()) {
                    
                    $response = flash_message('Success', true);
                }

            } catch (\ClientException $e) {
                
                $response = flash_message($e->getMessage());
                return response()->json($response);
            }

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function getFeedback()
    {
        $response = flash_message('The authentication has failed please login again.');

        if ($this->request->wantsJson() || $this->request->isJson()) {

            $token = $this->request->header('token');
            $memberLogin = $this->memberLogin->findBy('token', $token);
            
            if (!$memberLogin) {
                
                return response()->json($response);
            }
            
            $jsonData = $this->request->json()->all();

            try {
                
                $comments = $this->eorderComment->orderBy('id', 'desc')->limit(100)->get();

                $response = flash_message('Success', true);
                $feedbacks = [];
                foreach ($comments ?? [] as $key => $comment) {
                    $eorder = json_decode($comment->content, true);
                    $feedbacks[] = [
                        'id' => $eorder['e_id'],
                        'type' => $eorder['type'],
                        'status' => $eorder['stat'],
                        'cus_name' => $eorder['cus_name'],
                        'doc_name' => $eorder['doc_name'],
                        'code' => $eorder['e_code'],
                        'patient_name' => $eorder['pat_name'],
                        'rate' => $comment->rate
                    ];
                }

                $response['data'] = $feedbacks;
                
            } catch (\ClientException $e) {
                
                $response = flash_message($e->getMessage());
                return response()->json($response);    
            }

            return response()->json($response);
        }
    }
}
