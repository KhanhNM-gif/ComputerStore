<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields= $request->validate([
            'phone'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=> 'required|string'
        ]);

        $user=User::create([
            'phone'=>$fields['email'],
            'name'=>$fields['name'],
            'password'=>bcrypt($fields['password'])
        ]);

        $token=$user->createToken('myapptoken')->plainTextToken;

        $response=[
            'user'=>$user,
            'token'=>$token
        ];

        return Response($response,201);
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $request = request();
        $token = $request->bearerToken();

        if (User::attempt($credentials)) {
            $user=User::where('phone',$credentials['phone']) -> first();

            $token=$user->createToken('myapptoken')->plainTextToken;

            $response=[
                'user'=>$user,
                'token'=>$token
            ];

            return Response($response,201);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
