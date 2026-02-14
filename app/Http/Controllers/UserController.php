<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function store(Request $request){

    //create a new user

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'

        ]);


        $user = User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        ]);

    return response()->json($user, 201);
    }
}
