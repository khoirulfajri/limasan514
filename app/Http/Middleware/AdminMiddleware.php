<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{

    public function handle($request, Closure $next)
    {

        if (!Auth::check()) {
            return redirect('/admin/login');
        }

        if (!in_array(Auth::user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        return $next($request);
    }
}
