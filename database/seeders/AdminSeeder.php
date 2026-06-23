<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@bankuntar.com'], 
            [
                'name'              => 'Admin Bank Untar',
                'no_hp'             => '082114567890',     
                'nik'               => '3171000000000000',  
                'password'          => Hash::make('ZaAdmin123'), 
                'status'            => 'active', 
                'pin'               => null, 
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
