<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
class RegisterController extends Controller
{
    


    
    public function register (Request $request)
    {

       
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required',
            'password'  => 'required|min:3',        
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
       
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'email_verified_at' => now(),         
            'password'  => bcrypt($request->password),
       

        ]);   
        $user->role_id = 4;
        $user->save();
        $token = $user->createToken('ApiToken')->plainTextToken;
        return response()->json([
            'posisi' => '',
            'status' => 'success',
            'message' => 'Register Success!',
            'token'    => $token,  
        ]);

    }
}
