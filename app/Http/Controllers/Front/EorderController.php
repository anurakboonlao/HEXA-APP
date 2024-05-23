<?php

namespace App\Http\Controllers\Front;

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
    public function getOrders(Request $request, $id)
    {
        $response = [];

        if ($request->has('status') || $request->input('status')) {

            $status = $request->input('status');

            $member = $this->member->find($id);

            $keyword = $request->input('key') ?? "";
            
            try {

                if ($status == 'arrival') {
    
                    $comments = $this->eorderComment
                        ->where('member_id', $member->id)
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
                    $response['member'] = $member;

                    return response()->json($response);
                }

                if ($member->type == 'doctor') {

                    $response = flash_message('Success', true);
                    $response['data'] = [];

                    //foreach ($member->member->contacts ?? [] as $contact) {

                        $result = $this->httpRequest->request('GET', $this->eorderUrl, [
                            'query' => [
                                'filter' => 'doc',
                                'id' => $member->customer_verify_key,
                                'limit' => ($status == 'active') ? 10000 : 10000,
                                'stat' => ($status == 'active') ? 'A' : 'C',
                                'fine' => $keyword ?? ''
                            ]
                        ]);

                        $response['data'] = array_merge($response['data'], ($status == 'active' || $status == 'complete') ? map_eorder_response($result->getBody()->getContents(), $status) : []);
                        $response['member'] = $member;
                    //}

                } else {

                    $result = $this->httpRequest->request('GET', $this->eorderUrl, [
                        'query' => [
                            'filter' => 'cus',
                            'id' => $member->eo_cus_id,
                            'limit' => ($status == 'active') ? 10000 : 10000,
                            'stat' => ($status == 'active') ? 'A' : 'C',
                            'fine' => $keyword ?? ''
                        ]
                    ]);
                        
                    $response = flash_message('Success', true);
                    $response['data'] = ($status == 'active' || $status == 'complete') ? map_eorder_response($result->getBody()->getContents(), $status) : [];
                    $response['member'] = $member;
                }
                
            } catch (\ClientException $e) {
                
                $response = flash_message($e->getMessage());
                return response()->json($response);    
            }

            if ($status == 'active') {

                return view('front.order.get_myorder_inprocess', $response);
                //return response()->json($response);

            }else {

                return view('front.order.get_myorder_complete', $response);
                //return response()->json($response);
            }
        }
    }

    /**
     * 
     */
    public function getOrderDetail(Request $request)
    {
        $response = [];

        $param = $this->request->all();

        $member = $this->member->find($param['member_id']);

        try {

            $result = $this->httpRequest->request('GET', $this->eorderUrl, [
                'query' => [
                    'filter' => 'order',
                    'id' => $param['order_id']
                ]
            ]);
            
            $response = flash_message('Success', true);
            $response['data'] = map_eorder_detail_response($result->getBody()->getContents());
            $response['data']['comment'] = $this->eorderComment->find($param['order_id']) ?? null;
            
        } catch (\ClientException $e) {
            
            $response = flash_message($e->getMessage());
            return response()->json($response);
        }

        if ($param['order_status'] == 'inprocess') {

            return view('front.order.get_myorder_inprocess_detail', $response);

        }else{

            return view('front.order.get_myorder_complete_detail', $response);

        }        
    }

    /**
     * 
     */
    public function addComment(Request $request)
    {
        $response = [];

        $param = $this->request->all();

        $member = $this->member->find($param['member_id']);

        $validator = Validator::make($param, [
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
                    'id' => $param['order_id']
                ]
            ]);

            $content = json_decode($result->getBody()->getContents(), true);
            $comment = $this->eorderComment->find($param['order_id']) ?? $this->eorderComment;
            $comment->id = $param['order_id'];
            $comment->rate = $param['rate'] ?? $param['rating'];
            $comment->message = $param['message'] ?? '';
            $comment->member_id = $param['member_id'];
            $comment->content = json_encode($content['data'][0]);

            if ($comment->save()) {
                
                $response = flash_message('ขอขอบพระคุณคุณหมอที่ให้คำแนะนำ บริษัทฯจะนำไปพัฒนาการทำงานให้ดียิ่งขึ้น', true);
                return redirect()->back()->with('modal', 5);
            }

        } catch (\ClientException $e) {
            
            $response = flash_message($e->getMessage());
            return response()->json($response);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     */
    public function getFeedback()
    {
        
    }
}
