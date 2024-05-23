<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\Controller as BaseController;

use Log;

class GcmController extends BaseController
{
    public $urlIos;
    public $urlAndroid;
    public $serverKey;

    public function __construct()
    {
        $this->urlIos = "https://fcm.googleapis.com/fcm/send";
        $this->urlAndroid = "https://fcm.googleapis.com/fcm/send";
        $this->serverKey = env('GCM_KEY');
    }

    /**
     * 
     * 
     */
    public function sendNotificationIos($notification = [])
    {
        $url = $this->urlIos;
        $registrationIds = [$notification['token']];
        $serverKey = $this->serverKey;
        $notification = [
            'title' => $notification['title'],
            'message' => $notification['message'],
            'sound' => 'default',
            'badge' => '1'
        ];

        $arrayToSend = [
            'registration_ids' => $registrationIds, 
            'notification' => $notification,
            'priority' => 'high'
        ];

        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key='. $serverKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        if ($result === FALSE) 
        {
            Log::error(curl_error($ch));
            return false;
        }

        curl_close($ch);

        Log::info($result);
        return $result;
    }

    /**
     * 
     * 
     */
    public function sendNotificationAndroid($notification = [])
    {
        $url = $this->urlAndroid;
        $registrationIds = $notification['token'];
        $serverKey = $this->serverKey;
        $notification = [
            'title' => $notification['title'],
            'message' => $notification['message'],
            'sound' => 'default',
            'badge' => '1',
            'sound'	=> 1,
        ];

        $fields = [
            'to' => $registrationIds,
            'notification' => $notification,
            'data'	=> $notification
        ];
            
        $headers = [
            'Authorization: key=' . $this->serverKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        
        if ($result === FALSE) 
        {
            Log::error(curl_error($ch));
            return false;
        }

        curl_close($ch);

        Log::info($result);
        return $result;
    }

    /**
     * 
     * 
     */
    public function testSendNotification(Request $request)
    {
        $params = $request->all();

        $notification = [
            'token' => $params['token'],
            'title' => $params['title'],
            'message' => $params['message']
        ];

        if ($params['device_type'] == 'android') {

            $response = $this->sendNotificationAndroid($notification);
        } else {

            $response = $this->sendNotificationIos($notification);
        }

        return $response;
    }
}