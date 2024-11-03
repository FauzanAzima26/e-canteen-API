<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registration(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:255',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('myApp');

        $userResponse = [
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token->plainTextToken
        ];

        return new ResponseResource(true, 'User Created', $userResponse, [
            'code' => 201
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:255',
        ]);

        if (!auth()->attempt($data)) {
            return new ResponseResource(false, 'Credentials not match', [], [
                'code' => 401
            ], 401);
        }   

        $user = auth()->user();   
        
        // $user->tokens()->where('id', $tokenId)->delete();

        if ($user->tokens()->count() > 0) {
            $user->tokens()->delete();
        }

        $userResponse = [
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->createToken('myApp', ['*'], now()->addWeek())->plainTextToken,
          ];
        return new ResponseResource(true, 'User Logged In', $userResponse, [
            'expired_at' => $user->tokens()->first()->expires_at
        ], 200);
    }  

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return new ResponseResource(true, 'User Logged Out', null, [
            'code' => 200
        ], 200);
    }
}   
