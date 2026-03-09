<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index()
    {
        return view('frontend.page.home', [
            'title' => 'Home',
        ]);

    }
    public function login()
    {
        return view('frontend.page.login', [
            'title' => 'Login',
        ]);

    }
     public function register()
    {
        return view('frontend.page.register', [
            'title' => 'Register',
        ]);
    }
}
