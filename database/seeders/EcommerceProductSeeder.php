<?php

namespace Database\Seeders;

use App\Models\EcommerceProduct;
use Illuminate\Database\Seeder;

class EcommerceProductSeeder extends Seeder
{
    public function run(): void
    {
        $pulsa = [
            ['provider' => 'Telkomsel', 'nominal' => 10000],
            ['provider' => 'Telkomsel', 'nominal' => 25000],
            ['provider' => 'Indosat', 'nominal' => 10000],
            ['provider' => 'XL', 'nominal' => 10000],
        ];
        foreach ($pulsa as $p) {
            EcommerceProduct::create([
                'category' => 'pulsa',
                'provider' => $p['provider'],
                'name' => 'Pulsa ' . number_format($p['nominal'], 0, ',', '.'),
                'nominal' => $p['nominal'],
                'price' => $p['nominal'] + 1500,
            ]);
        }

        $token = [50000, 100000, 200000];
        foreach ($token as $t) {
            EcommerceProduct::create([
                'category' => 'token_listrik',
                'provider' => 'PLN',
                'name' => 'Token Listrik ' . number_format($t, 0, ',', '.'),
                'nominal' => $t,
                'price' => $t + 2500,
            ]);
        }

        $air = [50000, 100000];
        foreach ($air as $a) {
            EcommerceProduct::create([
                'category' => 'air',
                'provider' => 'PDAM',
                'name' => 'Bayar Air ' . number_format($a, 0, ',', '.'),
                'nominal' => $a,
                'price' => $a + 2000,
            ]);
        }
    }
}