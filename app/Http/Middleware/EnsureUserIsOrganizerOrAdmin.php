<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOrganizerOrAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        // Izinkan jika Role Admin atau Organizer
        if ($user->role === 'admin' || $user->role === 'organizer') {
            return $next($request);
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang mengelola dashboard ini.');
    }
}