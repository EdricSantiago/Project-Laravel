<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function setupPin(Request $request) {
        $request->validate([
            'pin' => 'required|numeric|digits:6'
        ], [
            'pin.digits' => 'PIN Harus terdiri Dari 6-digit Angka.'
        ]);

        $user = Auth::user();
        $user->pin = Hash::make($request->pin); 
        $user->save();

        return back()->with('success', 'PIN Berhasil Dibuat. Slay!');
    }

    public function freezeAccount() {
        $user = Auth::user();
        $user->status = 'suspended'; 
        $user->save();

        return back()->with('success', 'Akun Dibekukan, Semua Transaksi Diblokir. Panic mode activated!');
    }

    public function changePin(Request $request) {
        $request->validate([
            'oldPin' => 'required',
            'newPin' => 'required|numeric|digits:6'
        ], [
            'newPin.digits' => 'PIN Baru Harus terdiri Dari 6-digit Angka.'
        ]);

        $user = Auth::user();

        if ($user->status === 'suspended') return back()->withErrors(['error' => 'Akun sedang dibekukan. Tidak bisa mengganti PIN.']);
        if (!$user->pin) return back()->withErrors(['error' => 'PIN Belum Setup, Silahkan Buat Pin Terlebih Dahulu']);

        if (!Hash::check($request->oldPin, $user->pin)) {
            $user->failed_pin_attempts += 1; 
            
            if ($user->failed_pin_attempts >= 3) {
                $user->status = 'suspended'; 
                $user->save();
                return back()->withErrors(['error' => 'PIN Salah 3x, Akun Otomatis Dibekukan. Broke energy!']);
            }
            $user->save();
            return back()->withErrors(['error' => 'PIN Lama Salah! Sisa percobaan: ' . (3 - $user->failed_pin_attempts)]);
        }

        $user->failed_pin_attempts = 0;
        $user->pin = Hash::make($request->newPin);
        $user->save();

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
                return back()->withErrors(['error' => 'Akun Otomatis Dibekukan : 3x salah PIN.']);
            }
            $user->save();
            return back()->withErrors(['error' => 'PIN Salah! Sisa percobaan : ' . (3 - $user->failed_pin_attempts)]);
        }

        $user->failed_pin_attempts = 0;
        $user->save();
        
        $request->session()->put('pin_verified', true);
        return back()->with('success', 'Autentikasi Berhasil. VIP access granted!');
    }
}