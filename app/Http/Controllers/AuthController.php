<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function loginProcess(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            if (Auth::user()->role != 'admin') {
                Auth::logout();
                return back()->with('error', 'Akun bukan admin');
            }

            return redirect('/admin/dashboard');
        }

        return back()->with('error', 'Email atau password salah');
    }

    public function logout()
    {

        Auth::logout();

        return redirect('/admin/login');
    }
}
