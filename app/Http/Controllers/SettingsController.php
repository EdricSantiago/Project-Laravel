<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function securityLog()
{
    $logs = \App\Models\Security::where('user_id', Auth::id())
        ->latest()
        ->paginate(20);
    return view('settings.security-log', compact('logs'));
}
}
