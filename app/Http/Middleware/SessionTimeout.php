<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
public function handle(Request $request, Closure $next)
{

    if ($request->routeIs('login', 'register')) {
        return $next($request);
    }

    if (Auth::check()) {
        $timeout = 15 * 60; // 15 minutes in seconds

        if ($request->session()->has('last_activity')) {
            $lastActivity = $request->session()->get('last_activity');

            if (time() - $lastActivity > $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['email' => 'Sesi Anda telah berakhir karena tidak aktif.']);
            }
        }

        $request->session()->put('last_activity', time());
    }

    return $next($request);
}
};