<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Security; 

class SecurityController extends Controller
{
    public function setupPin(Request $request) {
        $request->validate(['pin' => 'required|numeric|digits:6'], ['pin.digits' => 'PIN Harus 6-digit Angka.']);

        $user = Auth::user();
        $user->pin = Hash::make($request->pin); 
        $user->save();

        Security::create(['user_id' => $user->id, 'action' => 'SETUP_PIN', 'ip_address' => $request->ip()]);

        return back()->with('success', 'PIN Berhasil Dibuat. ');
    }

    public function freezeAccount(Request $request) {
        $user = Auth::user();
        $user->status = 'suspended'; 
        $user->save();

        Security::create(['user_id' => $user->id, 'action' => 'ACCOUNT_FROZEN_PANIC', 'ip_address' => $request->ip()]);

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
                $user->save();
                Security::create(['user_id' => $user->id, 'action' => 'AUTO_SUSPEND_WRONG_PIN_3X', 'ip_address' => $request->ip()]);
                return back()->withErrors(['error' => 'PIN Salah 3x, Akun Otomatis Dibekukan. ']);
            }
            $user->save();
            Security::create(['user_id' => $user->id, 'action' => 'FAILED_PIN_CHANGE', 'ip_address' => $request->ip()]);
            return back()->withErrors(['error' => 'PIN Lama Salah! Sisa percobaan: ' . (3 - $user->failed_pin_attempts)]);
        }

        $user->failed_pin_attempts = 0;
        $user->pin = Hash::make($request->newPin);
        $user->save();

        Security::create(['user_id' => $user->id, 'action' => 'PIN_CHANGED', 'ip_address' => $request->ip()]);
        return back()->with('success', 'PIN Berhasil Diperbarui.');
    }

    public function verifyPin(Request $request) {
        $request->validate(['pin' => 'required']);
        $user = Auth::user();

        if ($user->status === 'suspended') return back()->withErrors(['error' => 'Akun sedang dibekukan.']);

        if (!Hash::check($request->pin, $user->pin)) {
            $user->failed_pin_attempts += 1;
            
            if ($user->failed_pin_attempts >= 3) {
                $user->status = 'suspended';
                $user->save();
                Security::create(['user_id' => $user->id, 'action' => 'AUTO_SUSPEND_WRONG_PIN_3X', 'ip_address' => $request->ip()]);
                return back()->withErrors(['error' => 'Akun Otomatis Dibekukan : 3x salah PIN.']);
            }
            $user->save();
            Security::create(['user_id' => $user->id, 'action' => 'FAILED_PIN_VERIFY', 'ip_address' => $request->ip()]);
            return back()->withErrors(['error' => 'PIN Salah! Sisa percobaan : ' . (3 - $user->failed_pin_attempts)]);
        }

        $user->failed_pin_attempts = 0;
        $user->save();
        
        $request->session()->put('pin_verified', true);
        Security::create(['user_id' => $user->id, 'action' => 'PIN_VERIFIED_FOR_TX', 'ip_address' => $request->ip()]);
        
        return back()->with('success', 'Autentikasi Berhasil. ');
    }
}