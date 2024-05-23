<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Socialite;
use App\User;
use DB;
use Auth;

class FacebookLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(User $modelUser)
    {
        $socialData = Socialite::driver('facebook')->user();

        if (!$socialData) {

            return redirect('signin');
        }

        $user = $modelUser->where('email', $socialData->email)->first();
        if ($user) {

            $user->social_id = $socialData->id;
            $user->social_token = $socialData->token;
            $user->login_type = 'facebook';

            if ($user->save()) {

                Auth::login($user);

                return redirect('customer');
            }

            return redirect('signin');
        }

        $modelUser->social_id = $socialData->id;
        $modelUser->login_type = 'facebook';
        $modelUser->social_token = $socialData->token;
        $modelUser->email = $socialData->email;
        $modelUser->first_name = $socialData->nickname;
        $modelUser->last_name = $socialData->name;
        
        if ($modelUser->save()) {

            Auth::login($modelUser);
            return redirect('customer');
        }

        return redirect('signin');
    }
}