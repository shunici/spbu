<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\PasswordReset;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    public function sendToken ( Request $request)
    {
        

        $user = User::where('email', $request->email)->first();
        if(!isset ($user->id) ) {
            return response()->json(['error' => 'user tidak valid'], 401);
        }
        $token = Str::random(40);     
        $passwordReset = new PasswordReset(); 
        $passwordReset->email = $user->email;
        $passwordReset->token = $token;
        $passwordReset->save();
        return response()->json(['data' => $token]);

    }

    public function validasiToken(Request $request)
    {
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        if( !isset($passwordReset->email)) {
            return response()->json(['error' => 'token invalid']);
        }
        $user = User::where('email', $passwordReset->email)->first();
        return response()->json($user->id, 200);
    }

    public function resetPassword(Request $request)
    {
        $user = User::find($request->user_id);
        $passwordReset = passwordReset::where('email', $user->email)->first();
        $passwordReset->delete();

        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['status' => $user]);
    }
}
