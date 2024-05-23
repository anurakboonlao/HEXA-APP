<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Mail;
use Intervention\Image\ImageManagerStatic as Image;

use App\Http\Controllers\GcmController as GcmService;

use DB;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResetPasswordEmail($user, $password)
    {   
        try {

            $data['user'] = $user;
            $data['password'] = $password;
            $sendMal = Mail::send('admin.elements.email.reset_password', $data, function($mail) use ($data) {
                $mail->from('hexaceramapp@gmail.com', 'Hexa Ceram');
                $mail->to($data['user']['email'], $data['user']['name']);
                $mail->replyTo('hexaceramapp@gmail.com', 'Hexa Ceram');
                $mail->subject('Reset password.');
            });
        
            return (Mail::failures()) ? false : true;
        
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function sendEmailPasswordToMember($member, $password, $isIphone = false)
    {   
        try {

            $data['is_iphone'] = $isIphone;
            $data['member'] = $member;
            $data['password'] = $password;
            $sendMal = Mail::send('admin.elements.email.new_password', $data, function($mail) use ($data, $member) {
                $mail->from('hexaceramapp@gmail.com', 'Hexa Ceram');
                $mail->to($member->email, $member->name);
                $mail->replyTo('hexaceramapp@gmail.com', 'Hexa Ceram');
                $mail->subject('Welcome to Hexa Ceram application.');
            });
        
            return (Mail::failures()) ? false : true;
        
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function uploadImage($file, $path)
    {
        $status = true;
        $extension = $file->extension();
        $newFileName = uniqid('file_') . '.' . $extension;

        try {
            $resize = Image::make($file->getRealPath());
            $width = Image::make($file->getRealPath())->width();
            $height = Image::make($file->getRealPath())->height();

            $resize->resize($width, $height);
            $resize->save(public_path('uploads/' . $path . '/'. $newFileName));

            $this->createThumbnailImage($file, $path, $newFileName);
        } catch(\Exception $e) {
            $newFileName = $e->getMessage();
            $status = false;
        }

        return [
            'status' => $status,
            'file_name' => $newFileName
        ];
    }

    public function createThumbnailImage($file, $path, $fileName)
    {
        $img = Image::make($file->getRealPath());
        $img->resize(null, 300, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save(public_path('uploads/' . $path . "/thumbnail/". $fileName));
    }

    public function uploadFile($file, $path)
    {
        $status = true;
        $newFileName = $file->getClientOriginalName();

        try {
            $file->move(public_path('uploads/' . $path . '/'), $file->getClientOriginalName());
        } catch(\Exception $e) {
            $newFileName = $e->getMessage();
            $status = false;
        }

        return [
            'status' => $status,
            'file_name' => $newFileName
        ];
    }

    public function showFile($path = null, $fileName = null) 
    {
        $pathToFile = storage_path('app/' . $path .'/'. $fileName);
        if (file_exists($pathToFile)) {
            return response()->file($pathToFile);
        }

        return "file notfound";
    }

    public function editField()
    {
        $responseMessage = [
            'status' => false,
            'message' => 'Error'
        ];

        $table = request()->input('table') ?? null;
        $field = request()->input('field') ?? null;
        $id = request()->input('id') ?? null;
        $value = request()->input('value') ?? null;
        $type = request()->input('type') ?? null;

        switch ($type) {
            case 'date':
                $value = date('Y-m-d', strtotime($value));
                break;
            
            default:
                $value = strip_tags($value);
                break;
        }

        if ($table && $field && $id) {

            $data = DB::table($table)->where('id', $id)->first();
            if ($data) {
                DB::table($table)->where('id', $id)->update([
                    $field => $value
                ]);

                $data = DB::table($table)->where('id', $id)->first();

                $data2 = json_encode($data);
                $data = json_decode($data2, true);

                $responseMessage = [
                    'status' => true,
                    'message' => 'Succss',
                    'field_value' => $data[$field],
                    'data' => $data
                ];
            }

        }

        return response()->json($responseMessage);
    }

    public function restore($model, $id)
    {
        try {

            switch ($model) {
                case 'group':
                    $restored = Group::withTrashed()->find($id)->restore();
                    break;
                case 'member':
                    $restored = Member::withTrashed()->find($id)->restore();
                    break;
                case 'policy':
                    $restored = Policy::withTrashed()->find($id)->restore();
                    break;
                case 'rice':
                    $restored = Rice::withTrashed()->find($id)->restore();
                    break;
                default:
                    $restored = false;
                    break;
            }
            
            if ($restored) {

                return "success";
            }
            
            return json_encode($restored);
        
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function dashboard()
    {
        $response = [];

        return view('admin.dashboard', $response);
    }

    /**
     * 
     * 
     */
    public function sendNotificationToDevice($deviceType, $deviceToken, $notification)
    {
        /*if (empty($deviceToken)) {

            return;
        }*/

        $gcmService = new GcmService;
        
        $notificationData = [
            'token' => $deviceToken,
            'title' => $notification['title'],
            'message' => $notification['message']
        ];
        
        try {

            if ($deviceType == 'android') {
                
                $gcmService->sendNotificationAndroid($notificationData);
            } else {
                
                $gcmService->sendNotificationIos($notificationData);
            }
        
        } catch (\Exception $e) {

            Log::error($e->getMessage());
        }
    }

    /**
     * 
     * 
     */
    public function testPushNotification()
    {
        $gcmService = new GcmService;

        $deviceType = request()->input('device_type') ?? 'android';
        
        $notificationData = [
            'token' => request()->input('device_token') ?? '',
            'title' => request()->input('title') ?? '',
            'message' => request()->input('message') ?? ''
        ];
        
        try {

            if ($deviceType == 'android') {
                
                $gcmService->sendNotificationAndroid($notificationData);
            } else {
                
                $gcmService->sendNotificationIos($notificationData);
            }

            return "success";
        
        } catch (\Exception $e) {

            Log::error($e->getMessage());

            return $e->getMessage();
        }
    }
}