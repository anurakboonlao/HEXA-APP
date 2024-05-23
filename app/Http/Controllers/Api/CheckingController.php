<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Setting;
use App\Member;
use App\MemberLogin;
use App\Checking;
use App\Product;

use DB;

use Validator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class CheckingController extends Controller
{
    public $request;
    public $setting;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $checking;
    public $product;
    public $eorderUrl;
    public $httpRequest;

    public function __construct(
        Setting $setting,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        Checking $checking,
        Product $product
    ) {
        $this->setting = $setting;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->checking = $checking;
        $this->product = $product;
        $this->eorderUrl = env('EORDER_URL', 'http://e-order.netforce.co.th/testeorder/eorder/api.php');
        $this->httpRequest = new Client();
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
                
                return response()->json($response);
            }

            $checkings = $this->checking
                ->where([
                    ['member_id', $memberLogin->member_id]
                ])
                ->orderBy('id', 'desc')
                ->get();
            
            $response = flash_message('success', true);
            $response['data']['checkings'] = map_response_checking($checkings);

            return response()->json($response);
        }
    }

    /**
     * 
     * 
     */
    public function store()
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
                'lat' => 'required|numeric',
                'long' => 'required|numeric',
                'location' => 'required',
                //'time' => 'required'
            ]);
        
            if ($validator->fails()) {

                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $this->checking->member_id = $memberLogin->member_id;
            $this->checking->location = $jsonData['location'];
            $this->checking->time = date('Y-m-d H:i:s');//$jsonData['time'];
            $this->checking->lat = $jsonData['lat'];
            $this->checking->long = $jsonData['long'];
            $this->checking->note = $jsonData['note'] ?? '';

            if ($this->checking->save()) {

                $response = flash_message('CHECK IN เรียบร้อยแล้ว', true);
            }

            return response()->json($response);
        }
    }
}
