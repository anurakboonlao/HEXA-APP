<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Socialite;

use Session;

use Auth;
use App\Member;

class SocialLoginController extends Controller
{
    public $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function facebookRedirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function facebookHandleProviderCallback()
    {
        $user = Socialite::driver('facebook')->user();

        if (empty($user->id)) {

            $response['message'] = [
                'status' => true,
                'message' => "Success."
            ];

            return redirect()->back()->with('message', $response['message']);
        }

        $memberData = [];

        $user->email = (empty($user->email)) ? $user->id . "@hexaceram.com" : $user->email;

        $findMember = $this->member->where('email', $user->email)->first();

        if(!$findMember) { 

            $this->member->email = $user->email;
            $this->member->name = $user->name;
            $this->member->role = 1;
            $this->member->username = $user->email;
            $this->member->password = md5('Aa123456');

            if (!$this->member->save()) {

                $response['message'] = [
                    'status' => false,
                    'message' => "มีบางอย่างผิดพลาด กรุณาลองใหม่",
                    'data' => null
                ];

                return redirect()->back()->with('message', $response['message']);
            }

            $memberData = $this->member;
        } else {

            $memberData = $findMember;
        }

        Session::put('member', $memberData);

        return redirect('front/dashboard');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleRedirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function googleHandleProviderCallback()
    {
        $user = Socialite::driver('google')->user();

        
        if (empty($user->id)) {
            
            $response['message'] = [
                'status' => true,
                'message' => "Success."
            ];
            
            return redirect()->back()->with('message', $response['message']);
        }
        
        $memberData = [];

        $user->email = (empty($user->email)) ? $user->id . "@hexaceram.com" : $user->email;

        $findMember = $this->member->where('email', $user->email)->first();

        if(!$findMember) { 

            $this->member->email = $user->email;
            $this->member->name = $user->name;
            $this->member->role = 1;
            $this->member->username = $user->email;
            $this->member->password = md5('Aa123456');

            if (!$this->member->save()) {

                $response['message'] = [
                    'status' => false,
                    'message' => "มีบางอย่างผิดพลาด กรุณาลองใหม่",
                    'data' => null
                ];

                return redirect()->back()->with('message', $response['message']);
            }

            $memberData = $this->member;
        } else {

            $memberData = $findMember;
        }

        Session::put('member', $memberData);

        return redirect('front/dashboard');
    }
}