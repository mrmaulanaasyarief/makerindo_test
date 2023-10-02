<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LogoutRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class ApiController extends Controller
{

    public function login(LoginRequest $request)
    {
        $login = Auth::Attempt($request->all());
        if ($login) {
            $user = Auth::user();
            $user->api_token = Str::random(60);
            $user->save();

            return response()->json([
                'response_code' => 200,
                'message' => 'Login Success',
                'content' => $user
            ]);
        }else{
            return response()->json([
                'response_code' => 404,
                'message' => 'Wrong Username or Password'
            ]);
        }
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => 1
        ]);

        return response()->json([
            'response_code' => 201,
            'message' => 'Register Success',
            'content' => $user
        ]);
        
    }
    public function logout(LogoutRequest $request)
    {
        if (User::where('api_token', '=', $request->api_token)->exists()) {
            $user = User::where('api_token', $request->api_token)->first();
            
            $user->api_token = "";
            $user->save();

            return response()->json([
                'response_code' => 200,
                'message' => 'Logged out',
                'content' => $user
            ]);
        }else{
            return response()->json([
                'response_code' => 404,
                'message' => 'Unauthenticated'
            ]);
        }
    }
}
