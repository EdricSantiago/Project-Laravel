<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\Security;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

public function login(LoginRequest $request): RedirectResponse
{
    $user = User::where('email', $request->email)->first();

    if ($user && $user->locked_until && now()->lt($user->locked_until)) {
        $menitSisa = now()->diffInMinutes($user->locked_until) + 1;
        return back()->withErrors(['email' => "Akun terkunci. Coba lagi dalam {$menitSisa} menit."]);
    }

    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials, $request->boolean('remember'))) {
        if ($user) {
            $isMobile = collect(['Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone'])
                ->contains(fn($k) => stripos($request->userAgent(), $k) !== false);

            $user->failed_login_attempts += 1;

            if ($user->failed_login_attempts >= 5) {
                $user->locked_until = now()->addMinutes(30);
                $user->failed_login_attempts = 0;
                $user->save();
                Security::create([
                    'user_id'     => $user->id,
                    'action'      => 'AUTO_LOCKED_WRONG_PASSWORD_5X',
                    'ip_address'  => $request->ip(),
                    'user_agent'  => $request->userAgent(),
                    'device_type' => $isMobile ? 'mobile' : 'desktop',
                    'status'      => 'success',
                    'notes'       => 'Akun terkunci 30 menit karena 5x salah password.',
                ]);
                return back()->withErrors(['email' => 'Terlalu banyak percobaan. Akun terkunci 30 menit.']);
            }

            $user->save();
            Security::create([
                'user_id'     => $user->id,
                'action'      => 'FAILED_LOGIN',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'device_type' => $isMobile ? 'mobile' : 'desktop',
                'status'      => 'failed',
                'notes'       => 'Percobaan login gagal.',
            ]);
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah. Sisa percobaan: ' . (5 - $user->failed_login_attempts)]);
        }
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    $user = Auth::user();

    if (!$user->isActive()) {
        Auth::logout();
        return back()->withErrors(['email' => 'Akun Anda belum aktif atau telah diblokir.']);
    }

    $user->failed_login_attempts = 0;
    $user->locked_until = null;
    $user->save();

    $isMobile = collect(['Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone'])
        ->contains(fn($k) => stripos($request->userAgent(), $k) !== false);

    Security::create([
        'user_id'     => $user->id,
        'action'      => 'LOGIN_SUCCESS',
        'ip_address'  => $request->ip(),
        'user_agent'  => $request->userAgent(),
        'device_type' => $isMobile ? 'mobile' : 'desktop',
        'status'      => 'success',
        'notes'       => 'Login berhasil.',
    ]);

    $request->session()->regenerate();
    $request->session()->put('last_activity', time());

    return redirect()->intended(route('homepage'))
        ->with('success', 'Selamat datang, ' . $user->name . '!');
}

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $noRekening = User::generateAccountNumber();
    
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'no_hp'          => $request->no_hp,
            'nik'            => $request->nik,
            'password'       => Hash::make($request->password),
            'account_number' => $noRekening, 
            'pin'            => Hash::make($request->pin),
            'status'         => 'active',
        ]);

        \App\Models\Account::create([
            'user_id'        => $user->id,
            'account_number' => $noRekening,
            'balance'        => 0, 
            'status'         => 'active'
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('homepage')
            ->with('success', 'Registrasi berhasil! Nomor rekening Anda: ' . $noRekening);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
