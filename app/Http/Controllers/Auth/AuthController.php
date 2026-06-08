<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
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
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $user = Auth::user();

        if (!$user->isActive()) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda belum aktif atau telah diblokir.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }


    public function showRegister(): View
    {
        return view('auth.register');
    }

   public function register(RegisterRequest $request): RedirectResponse
    {
        // 1. Ambil nomor rekening acak sekali saja di sini
        $noRekening = User::generateAccountNumber();
    
        // 2. Daftarkan User baru
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'no_hp'          => $request->no_hp,
            'nik'            => $request->nik,
            'password'       => Hash::make($request->password),
            'account_number' => $noRekening, // Pakai variabel $noRekening
            'pin'            => Hash::make($request->pin),
            'status'         => 'active',
        ]);

        // 3. Daftarkan Akun Saldo dengan nomor rekening yang SAMA
        \App\Models\Account::create([
            'user_id'        => $user->id,
            'account_number' => $noRekening, // Pakai variabel $noRekening
            'balance'        => 0, 
            'status'         => 'active'
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
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