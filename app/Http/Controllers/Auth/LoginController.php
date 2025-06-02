<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
 

    public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        $auth = $request->except(['remember_me']);
        if (auth()->attempt($auth, $request->remember_me)) {            
            $token = auth()->user()->createToken('ApiToken')->plainTextToken;
            return response()->json([
                'posisi' => auth()->user(),
                'status' => 'success', 
                'token' => $token],
            200);
        }
        
        return response()->json(['status' => 'failed']);
    }


    public function logout(Request $request)
    {         
        Auth::logout();
        return response()->json(['status' => 'anda telah logout']);
    }

    public function cek(Request $request)
    {         
       
        return response()->json(auth()->check() );
    }


}
