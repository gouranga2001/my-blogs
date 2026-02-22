<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public function login(Request $request)
        {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return redirect()->route('admin.dashboard')
                    ->with('success', 'Logged in successfully!');
            }

            return back()->withErrors([
                'email' => 'Invalid credentials.'
            ])->onlyInput('email');
        }
}
