<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::all()
        ]);
    }

    public function show(User $user)
    {
        $blogs = $user->blogs()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->get();

        return view('user', compact('user', 'blogs'));
    }


    public function store(Request $request)
        {
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

            Auth::login($user);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Account created!');
        }

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
