<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Laravel\Socialite\Facades\Socialite as Socialite;
use App\SocialAccountService;

class SocialAuthController extends Controller
{

    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback(SocialAccountService $service, Request $request)
    {
        $fb_user = Socialite::driver('facebook')->user();
        $user = $service->createOrGetUser($fb_user);
        
        auth()->login($user);
        $request->session()->put('user', $user);
        $request->session()->put('fb_user', $fb_user);
        return redirect('/');
    }
    
    public function logout(Request $request)
    {
        $request->session()->forget('user');
        $request->session()->forget('fb_user');
        return redirect('/');
    }
    
}
