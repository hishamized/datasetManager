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

        $validatedData = $request->validate([
            'identifier' => 'required',
            'password' => 'required|min:8',
        ]);
        $credentials = [
            'password' => $validatedData['password']
        ];
        if (filter_var($validatedData['identifier'], FILTER_VALIDATE_EMAIL)) {

            $credentials['email'] = $validatedData['identifier'];
        } else {

            $credentials['username'] = $validatedData['identifier'];
        }

        if (Auth::attempt($credentials)) {

            return redirect()->route('dashboard')->with('success', 'You are logged in successfully!');
        }


        return redirect()->route('user.login')->with('error', 'Invalid credentials, please try again!');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.login')->with('success', 'You are logged out successfully!');
    }

    public function showSignUpPage()
    {
        if (Auth::check()) {
           if (Auth::user()->role == 'master' && Auth::user()->authorization == 'active') {
                return view('auth.signup');
            } else {
                return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page!');
            }
        } else {
            return redirect()->route('auth.login')->with('error', 'You need to login first!');
        }
    }

    public function addNewAdmin(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'You need to login first!');
        }
        if (Auth::user()->role != 'master' || Auth::user()->authorization != 'active') {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to view this page!');
        }
        $validatedData = $request->validate([
            'fullName' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'cpassword' => 'required|same:password',
            'masterPassword' => 'required',
            'dateOfBirth' => 'required|date',
            'authorization' => 'required|in:active,revoked',
            'role' => 'required|in:master,scholar',
        ]);

        $masterAdmin = User::where('id', Auth::id())->first();
        if ($masterAdmin && Hash::check($request->masterPassword, $masterAdmin->password)) {
            $user = new User();
            $user->fullName = $validatedData['fullName'];
            $user->username = $validatedData['username'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['password']);
            $user->dateOfBirth = $validatedData['dateOfBirth'];
            $user->authorization = $validatedData['authorization'];
            $user->role = $validatedData['role'];
            $user->save();
            return redirect()->route('showSignUpPage')->with('success', 'New admin added successfully!');
        } else {
            return redirect()->route('showSignUpPage')->with('error', 'Invalid master password!');
        }
    }
}
