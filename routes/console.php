<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─── Auto-Pay Asuransi ───────────────────────────────────────────────────────
// Jalan setiap tanggal 1 tiap bulan jam 08:00 pagi
// Pastikan server/cron sudah jalankan: * * * * * php artisan schedule:run
Schedule::command('insurance:autopay')
    ->monthlyOn(1, '08:00')
    ->withoutOverlapping()        // Tidak bisa jalan dua kali bersamaan
    ->runInBackground()           // Tidak block request lain
    ->appendOutputTo(storage_path('logs/insurance-autopay.log'));
