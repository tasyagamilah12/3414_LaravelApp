<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/admin/login');
        }

        // Izinkan admin dan organizer masuk dashboard
        if (!in_array(Auth::user()->role, ['admin', 'user'])) {
            abort(403);
        }

        return $next($request);
    }
}