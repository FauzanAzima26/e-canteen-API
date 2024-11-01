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

        $token = $user->createToken('myApp', ['categories:index', 'categories:show']);

        $userResponse = [
            'name' => $user->name,
            'email' => $user->email,
            'token' => $token->plainTextToken
        ];

        return new ResponseResource(true, 'User Created', $userResponse, [
            'code' => 201
        ], 201);
    }
}
