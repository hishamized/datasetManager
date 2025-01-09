<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'password' => 'required|min:8',
        ]);

        $user = User::where('username', $request->identifier)
            ->orWhere('email', $request->identifier)
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'You are logged in successfully!');
        } else {
            return redirect()->route('user.login')->with('error', 'Invalid credentials, please try again!');
        }

        return redirect()->route('user.login')->with('error', 'Invalid credentials, please try again!');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('user.login')->with('success', 'You are logged out successfully!');
    }
}
