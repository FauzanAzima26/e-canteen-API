<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ResponseResource;

class UserController extends Controller
{
    public function index()
    {
        $user = User::latest()->get();

        return new ResponseResource(true, 'List Data User', $user, [
            'total_user' => $user->count()
        ]);
    }
}
