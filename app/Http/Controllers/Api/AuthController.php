<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $validate = $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:5',
        ]);

        $creadentials = $request->only(['email','password']);

        if(auth()->attempt($creadentials)){
            $token = $request->user()->createToken('myToken')->plainTextToken;
            $u = [
                'name'=>auth()->user()->name,
                'email'=>auth()->user()->email,
                'access_token'=>$token,
            ];

            return response()->json(['messaage'=>'login success','user'=> $u],200);
        }

        return response()->json(['messaage'=>'email or password incorrect'],401);

    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(['messaage'=>'logout success'],200);

    }
}
