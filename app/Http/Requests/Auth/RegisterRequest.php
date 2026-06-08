<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_hp'                 => ['required', 'string', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,10}$/', 'unique:users'],
            'nik'                   => ['required', 'digits:16', 'unique:users'],
            'pin'                   => ['required', 'numeric', 'digits:6'],
            'konfirmasi_pin'       => ['required', 'numeric', 'digits:6', 'same:pin'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'no_hp.regex'        => 'Format nomor HP tidak valid (contoh: 08123456789).',
            'no_hp.unique'       => 'Nomor HP sudah terdaftar.',
            'nik.digits'         => 'NIK harus 16 digit angka.',
            'nik.unique'         => 'NIK sudah terdaftar.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'pin.digits'         => 'PIN  harus terdiri dari 6 digit angka.',
            'konfirmasi_pin.same' => 'Konfirmasi PIN tidak cocok.',
        ];
    }
}