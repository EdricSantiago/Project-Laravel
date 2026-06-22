<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Security;
use App\Models\Account;

class SecurityController extends Controller
{
    public function setupPin(Request $request) {
    $request->validate(['pin' => 'required|numeric|digits:6'], ['pin.digits' => 'PIN Harus 6-digit Angka.']);

    $user = Auth::user();

    if ($user->pin) return back()->withErrors(['error' => 'PIN sudah disetup sebelumnya. Gunakan fitur Ganti PIN.']);

    $user->pin = Hash::make($request->pin);
    $user->save();

    Security::create([
        'user_id'     => $user->id,
        'action'      => 'SETUP_PIN',
        'ip_address'  => $request->ip(),
        'user_agent'  => $request->userAgent(),
        'device_type' => $this->detectDevice($request->userAgent()),
        'old_value'   => null,
        'new_value'   => $user->pin,
        'status'      => 'success',
        'notes'       => 'User membuat PIN pertama kali.', 
    ]);
    return back()->with('success', 'PIN Berhasil Dibuat.');
}

    public function freezeAccount(Request $request) {
        $user = Auth::user();
        $user->status = 'suspended'; 
        $user->save();

        Account::where('user_id', $user->id)->update(['status' => 'suspended']);

        Security::create([
            'user_id' => $user->id, 
            'action' => 'ACCOUNT_FROZEN_PANIC', 
            'ip_address' => $request->ip(), 
            'user_agent' => $request->userAgent(), 
            'device_type' => $this->detectDevice($request->userAgent()), 
            'old_value' => 'active', 
            'new_value' => 'suspended', 
            'status' => 'success', 
            'notes' => 'Akun dibekukan oleh pengguna.']
        );

        return back()->with('success', 'Akun Dibekukan, Semua Transaksi Diblokir.');
    }

    public function changePin(Request $request) {
        $request->validate([
            'oldPin' => 'required',
            'newPin' => 'required|numeric|digits:6|different:oldPin'
        ], ['newPin.digits' => 'PIN Baru Harus 6-digit Angka.']);

        $user = Auth::user();

        if ($user->status === 'suspended') return back()->withErrors(['error' => 'Akun sedang dibekukan.']);
        if (!$user->pin) return back()->withErrors(['error' => 'PIN Belum Setup.']);

        if (!Hash::check($request->oldPin, $user->pin)) {
            $user->failed_pin_attempts += 1; 
            
            if ($user->failed_pin_attempts >= 3) {
                $user->status = 'suspended'; 
                $user->failed_pin_attempts = 0; 
                $user->save();
                Account::where('user_id', $user->id)->update(['status' => 'suspended']);
                Security::create([
                    'user_id' => $user->id, 
                    'action' => 'AUTO_SUSPEND_WRONG_PIN_3X', 
                    'ip_address' => $request->ip(), 
                    'user_agent' => $request->userAgent(), 
                    'device_type' => $this->detectDevice($request->userAgent()), 
                    'old_value' => 'active', 
                    'new_value' => 'suspended', 
                    'status' => 'success', 
                    'notes' => 'Akun otomatis dibekukan karena 3x salah PIN.'
                ]);
                return back()->withErrors(['error' => 'PIN Salah 3x, Akun Otomatis Dibekukan. ']);
            }
            $user->save();
            Security::create([
                'user_id' => $user->id, 
                'action' => 'FAILED_PIN_CHANGE', 
                'ip_address' => $request->ip(), 
                'user_agent' => $request->userAgent(), 
                'device_type' => $this->detectDevice($request->userAgent()), 
                'old_value' => $user->pin, 
                'new_value' => null, 
                'status' => 'failed', 
                'notes' => 'Percobaan perubahan PIN gagal.'
            ]);
            return back()->withErrors(['error' => 'PIN Lama Salah! Sisa percobaan: ' . (3 - $user->failed_pin_attempts)]);
        }

        $user->failed_pin_attempts = 0;
        $user->pin_changed_at = now();
        $oldPinHash = $user->pin;                
        $user->pin = Hash::make($request->newPin);
        $user->save();

        Security::create([
            'user_id' => $user->id, 
            'action' => 'PIN_CHANGED', 
            'ip_address' => $request->ip(), 
            'user_agent' => $request->userAgent(), 
            'device_type' => $this->detectDevice($request->userAgent()), 
            'old_value' => $oldPinHash, 
            'new_value' => $user->pin, 
            'status' => 'success', 
            'notes' => 'PIN berhasil diperbarui.'
        ]);
        return back()->with('success', 'PIN Berhasil Diperbarui.');
    }

    public function verifyPin(Request $request) {
    $request->validate(['pin' => 'required']);
    $user = Auth::user();

    if ($user->status === 'suspended') return back()->withErrors(['error' => 'Akun sedang dibekukan.']);

    if (!$user->pin) return back()->withErrors(['error' => 'PIN belum disetup. Silakan setup PIN terlebih dahulu.']);

    if (!Hash::check($request->pin, $user->pin)) {
        $user->failed_pin_attempts += 1;

        if ($user->failed_pin_attempts >= 3) {
            $user->status = 'suspended';
            $user->failed_pin_attempts = 0; 
            $user->save();
            Account::where('user_id', $user->id)->update(['status' => 'suspended']);
            Security::create([
                'user_id'     => $user->id,
                'action'      => 'AUTO_SUSPEND_WRONG_PIN_3X',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'device_type' => $this->detectDevice($request->userAgent()),
                'old_value'   => 'active',
                'new_value'   => 'suspended',
                'status'      => 'success',
                'notes'       => 'Akun otomatis dibekukan karena 3x salah PIN.',
            ]);
            return back()->withErrors(['error' => 'Akun Otomatis Dibekukan : 3x salah PIN.']);
        }

        $user->save();
        Security::create([
            'user_id'     => $user->id,
            'action'      => 'FAILED_PIN_VERIFY',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'device_type' => $this->detectDevice($request->userAgent()),
            'old_value'   => null,
            'new_value'   => null,
            'status'      => 'failed',
            'notes'       => 'Percobaan verifikasi PIN gagal.',
        ]);
        return back()->withErrors(['error' => 'PIN Salah! Sisa percobaan : ' . (3 - $user->failed_pin_attempts)]);
    }

    $user->failed_pin_attempts = 0;
    $user->save();

    $request->session()->put('pin_verified', true);
    Security::create([
        'user_id'     => $user->id,
        'action'      => 'PIN_VERIFIED_FOR_TX',
        'ip_address'  => $request->ip(),
        'user_agent'  => $request->userAgent(),
        'device_type' => $this->detectDevice($request->userAgent()),
        'old_value'   => null,
        'new_value'   => null,
        'status'      => 'success',
        'notes'       => 'PIN berhasil diverifikasi.',
    ]);

    return back()->with('success', 'Autentikasi Berhasil.');
}
    public function changePassword(Request $request) {
    $request->validate([
        'oldPassword' => 'required',
        'newPassword' => 'required|min:8|different:oldPassword'
    ], [
        'newPassword.min'       => 'Password baru minimal 8 karakter.',
        'newPassword.different' => 'Password baru tidak boleh sama dengan password lama.'
    ]);

    $user = Auth::user();

    if (!Hash::check($request->oldPassword, $user->password)) {
        return back()->withErrors(['error' => 'Password lama anda salah! Silahkan coba lagi.']);
    }

    $oldPasswordHash = $user->password; 
    $user->password = Hash::make($request->newPassword);
    $user->save();

    Security::create([
        'user_id'     => $user->id,
        'action'      => 'PASSWORD_CHANGED',
        'ip_address'  => $request->ip(),
        'user_agent'  => $request->userAgent(),
        'device_type' => $this->detectDevice($request->userAgent()),
        'old_value'   => $oldPasswordHash, 
        'new_value'   => $user->password, 
        'status'      => 'success',
        'notes'       => 'Password akun berhasil diperbarui.',
    ]);
    return back()->with('success', 'Password akun berhasil diperbarui.');
}
    public function getSecurityStatus(Request $request) {
        $user = Auth::user();

        $failedPinLogs = Security::where('user_id', $user->id)
            ->whereIn('action', ['FAILED_PIN_VERIFY', 'FAILED_PIN_CHANGE'])
            ->count();

        return response()->json([
            'is_suspended'      => $user->status === 'suspended',
            'has_pin'           => !is_null($user->pin),
            'failed_pin_attempts' => $user->failed_pin_attempts ?? 0,
            'failed_pin_logs'   => $failedPinLogs,
            'pin_changed_at'    => $user->pin_changed_at,
            'pin_cooldown_active' => $user->pin_changed_at
                ? now()->diffInHours($user->pin_changed_at) < 24
                : false,
        ]);
    }

    private function detectDevice(string $userAgent): string
{
    $mobile = ['Mobile', 'Android', 'iPhone', 'iPad', 'Windows Phone'];
    foreach ($mobile as $keyword) {
        if (stripos($userAgent, $keyword) !== false) {
            return 'mobile';
        }
    }
    return 'desktop';}
}