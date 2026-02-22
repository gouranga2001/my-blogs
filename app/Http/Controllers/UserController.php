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

    public function update(Request $request,User $user){

        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'bio' => 'nullable',
            'github' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'avatar' => 'nullable|image|max:2048'
        ]);

        $data = $request->only([
            'name',
            'username',
            'email',
            'bio',
            'github',
            'linkedin',
            
        ]);

        if($request->password){
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')){
            $path = $request->file('avatar')->store('avatars','public');
            $data['avatar_path'] = $path;

        }

        $user->update($data);

        return back()->with('success','profile updated successfully');
    }


}
