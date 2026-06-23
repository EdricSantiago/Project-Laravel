<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function securityLog()
{
    $logs = \App\Models\Security::where('user_id', Auth::id())
        ->latest()
        ->paginate(20);
    return view('security-log', compact('logs')); 
}
};