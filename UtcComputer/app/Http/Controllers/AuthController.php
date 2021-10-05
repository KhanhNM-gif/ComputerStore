<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields= $request->validate([
            'email'=>'required|string|unique:accounts,email|email|max:255',
            'name'=> 'required|string|max:255',
            'password'=> 'required|string|confirmed|min:6|max:255'
        ]);

        $user=Account::create([
            'email'=>$fields['email'],
            'name'=>$fields['name'],
            'password'=>password_hash($fields['password'],PASSWORD_DEFAULT)
        ]);

        $token=$user->createToken('myapptoken')->plainTextToken;

        $response=[
            'user'=>$user,
            'token'=>$token
        ];

        return Response($response,200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=>'required|string|email|max:255',
            'password'=> 'required|string|min:6|max:255'
        ]);

        $request = request();
        $token = $request->bearerToken();

        
        $user=Account::where('email',$credentials['email']) -> first();

        if($user==null){
            return response()->json([
                'message' => 'Email chưa được đăng kí trên hệ thống',
                "errors"=>[]], 201);
        }

        if(password_verify($credentials['password'],$user['password'])){
            $token=$user->createToken('myapptoken')->plainTextToken;
            $response=[
                'user'=>$user,
                'token'=>$token
            ];
    
            return Response($response,200);
        }
        return response()->json([
            'message' => 'Mật khẩu đăng nhập sai',
            "errors"=>[]], 201);
    }
}
