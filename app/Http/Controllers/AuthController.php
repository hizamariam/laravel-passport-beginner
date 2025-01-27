<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|unique:users|max:255',
            'password'=>'required|min:6'
        ]);

        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        $token=$user->create_token('auth_token')->access_token;

        return response([
            'token'=>$token
        ]);

    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email'=>'required|unique:users|max:255',
    //         'password'=>'required|min:6'
    //     ]);

    //     $user=User::where('email',$request->email)->first();

    //     if(!$user||!Hash::check($request->password,$user->password))
    //     {
    //         return response([
    //             'message'=>'The provided credentials are incorrect'
    //         ]);
    //     }
    //     $token=$user->create_token('auth_token')->access_token;

    //     return response([
    //         'token'=>$token
    //     ]);

    // }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response([
            'message' => 'The provided credentials are incorrect'
        ]);
    }

    $token = $user->createToken('auth_token')->accessToken;

    return response([
        'token' => $token
    ]);
}
 

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response([
            'message'=>'Logged out successful'
        ]);
    }
}
