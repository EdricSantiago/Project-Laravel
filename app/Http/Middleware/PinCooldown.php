<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinCooldown
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->pin_changed_at) {
            $cooldownJam = 24;
            $jamSejak = now()->diffInHours($user->pin_changed_at);

            if ($jamSejak < $cooldownJam) {
                $sisaJam = $cooldownJam - $jamSejak;
                return back()->withErrors(['error' => "Transaksi besar diblokir {$sisaJam} jam setelah PIN diubah."]);
            }
        }

        return $next($request);
    }
}