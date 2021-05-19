<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacebookLoginController extends Controller
{
    public function checkLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login');
        }
    }

    public function login(Request $request)
    {
        if ($request->type == "facebook") {
            $userInfo = $request->userInfo;
            // return $userInfo;
            $existedUser = User::where('remember_token', 'like', $userInfo['remember_token'])->first();
            if ($existedUser) {
                Auth::loginUsingId($existedUser->id, true);
                return route('dashboard');
            } else {
                $user = User::create($userInfo);
                Auth::loginUsingId($user->id, true);
                return route('dashboard');
            }
        } else {
            $phoneNumber = $request->phone_number;
            $uid = $request->uid;
            $existedUser = User::where('remember_token', 'like', $uid)->first();
            if ($existedUser) {
                Auth::loginUsingId($existedUser->id, true);
                return route('dashboard');
            } else {
                $user = new User();
                $user->name = "member_". uniqid();
                $user->remember_token = $uid;
                $user->phone_number = $phoneNumber;
                $user->save();
                Auth::loginUsingId($user->id, true);
                return route('dashboard');
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
