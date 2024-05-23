<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Setting;
use App\Member;
use App\MemberLogin;
use App\OrderPickup;
use App\Product;
use App\ZoneMember;

use DB;

use Validator;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class PickupController extends Controller
{
    public $request;
    public $setting;
    public $remoteMysql;
    public $member;
    public $memberLogin;
    public $orderPickup;
    public $product;
    public $eorderUrl;
    public $httpRequest;
    public $zoneMember;

    public function __construct(
        Setting $setting,
        Request $request,
        Member $member,
        MemberLogin $memberLogin,
        OrderPickup $orderPickup,
        Product $product,
        ZoneMember $zoneMember
    ) {
        $this->setting = $setting;
        $this->request = $request;
        $this->remoteMysql = DB::connection('mysql2');
        $this->member = $member;
        $this->memberLogin = $memberLogin;
        $this->orderPickup = $orderPickup;
        $this->product = $product;
        $this->eorderUrl = env('EORDER_URL', 'http://e-order.netforce.co.th/testeorder/eorder/api.php');
        $this->httpRequest = new Client();
        $this->zoneMember = $zoneMember;
    }

    /**
     * 
     * 
     */
    public function home()
    {
        $response = [];

        return view ('front.pickup.index', $response);
    }

    /**
     * 
     * 
     */
    public function confirm(Request $request, $id)
    {
        $response = [
            'status' => false,
            'message' => "Errors."
        ];

        $member = $this->member->find($id);

        $pickup = $this->orderPickup
            ->where('member_id', $member->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($this->request->isMethod('post')) {

            $params = $this->request->all();

            $validator = Validator::make($params, [
                'location' => 'required',
                'time' => 'required',
                'total_case' => 'required',
                'doctor_name' => 'required',
                'patient_name' => 'required'
            ]);

            if ($validator->fails()) {

                $response = [
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ];
    
                return redirect()->back()->with('message', $response);
            }

            $this->orderPickup->member_id = $member->id;
            $this->orderPickup->address = $params['location'];
            $this->orderPickup->time = $params['time'] ?? null;
            $this->orderPickup->total_case = $params['total_case'] ?? null;
            $this->orderPickup->doctor_name = $params['doctor_name'] ?? null;
            $this->orderPickup->patient_name = $params['patient_name'] ?? null;
            $this->orderPickup->lat = $pickup->lat ?? null;
            $this->orderPickup->long = $pickup->long ?? null;

            if ($this->orderPickup->save()) {

                $myZone = $this->zoneMember->where('member_id', $member->id)->first();

                $sales = $this->zoneMember
                    ->leftJoin('members', 'zone_members.member_id', '=', 'members.id')
                    ->where('zone_members.zone_id', $myZone->zone_id)
                    ->where('members.role', 3)
                    ->get();

                // dd($sales);

                foreach ($sales ?? [] as $sale) {
                    foreach ($sale->logins ?? [] as $login) {
                        $gg[] = $login->device_type.'/'.$login->device_token.' / '.$login->member->name ?? '';
                        $this->sendNotificationToDevice($login->device_type, $login->device_token, [
                            'title' => $member->name ?? " Pickup Notification",
                            'message' => 'มีการเรียกรับงานใหม่ กรุณาเข้าแอปเพื่อดูรายละเอียด'
                        ]);
                    }

                    // Line Send Message
                    if(!empty($sale->line_user_id)){
                        $this->sendPickupOrderToSaleLine($sale, $this->orderPickup);
                    }

                }

                $response = [
                    'status' => true,
                    'message' => "ได้รับคำสั่งเรียกรับงานแล้ว อยู่ระหว่างดำเนินการครับ"
                ];
            }

            return redirect()->back()->with('message', $response);
        }
        
        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     * 
     */
    public function cancelOrderPickup($id)
    {
        $response = [
            'message' => [
                'status' => false,
                'message' => "Errors."
            ]
        ];

        if($this->orderPickup->destroy($id)){
            $response['message'] = [
                'status' => true,
                'message' => "ยกเลิกการรับงานสำเร็จ."
            ];
        }

        return redirect()->back()->with('message', $response['message']);
    }

    private function sendPickupOrderToSaleLine($sale, $orderPickup){

            // dd($sale);
            $strAccessToken = env('LINE_ACCESS_TOKEN');
    
            $strUrl = "https://api.line.me/v2/bot/message/push";
    
            $arrHeader = array();
            $arrHeader[] = "Content-Type: application/json";
            $arrHeader[] = "Authorization: Bearer {$strAccessToken}";
            $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $strAccessToken);
    
    
            $line_user_id = $sale->line_user_id;
    
            $tmp_text = "";
    
            //$orderPickup->member_id = $member->id;
            $tmp_text .= "รับงาน \n";
            $tmp_text .= "สถานที่ :" . $orderPickup->address . "\n";
            $tmp_text .= "เวลา :" . $orderPickup->time . "\n";
            $tmp_text .= "จำนวนเคส :" . $orderPickup->total_case . "\n";
            $tmp_text .= "ทันตแพทย์ :" . $orderPickup->doctor_name . "\n";
            $tmp_text .= "คนไข้ :" . $orderPickup->patient_name . "\n";


            $arrPostData = array();
            // $arrPostData['replyToken'] = $arrJson['events'][0]['replyToken'];
            $arrPostData['to'] = $line_user_id;
            $arrPostData['messages'][0]['type'] = "text";
            $arrPostData['messages'][0]['text'] =  $tmp_text;
            // $arrPostData['type'] = "text";
            // $arrPostData['text'] =  $tmp_text;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$strUrl);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrPostData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close ($ch);
            // dd($result);
        
    }
}




