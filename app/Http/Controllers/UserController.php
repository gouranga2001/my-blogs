<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::all()
        ]);
    }

    public function show(string $username)
    {
        $user = User::where('username', $username)
                    ->with('blogs')
                    ->firstOrFail();

        return view('users.show', [
            'user' => $user
        ]);
    }
}
