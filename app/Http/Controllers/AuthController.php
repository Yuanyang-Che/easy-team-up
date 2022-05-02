<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Contracts\View\Factory;
use \Illuminate\Contracts\View\View;
use \Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (Auth::user()) {
            return redirect()->route('profile.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        //handle login
        $loginWasSuccessful = Auth::attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password')]
        );

        if ($loginWasSuccessful) {
            return redirect()->route('profile.index');
        }
        else {
            return redirect()->route('auth.login')->with('error', 'Invalid credentials');
        }
    }

    public function signupForm()
    {
        if (Auth::user()) {
            return redirect()->route('profile.index');
        }
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        $user = new User();

        $user->email = $request->input('email');
        $user->name = $request->input('username');
        $user->password = Hash::make($request->input('password')); //encrypt using bcrypt

        $userRole = Role::where('slug', '=', 'user')->first();
        $user->role()->associate($userRole);

        $user->save();

        Auth::login($user);
        return redirect()->route('profile.index');
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        return redirect()->route('auth.login');
    }
}
