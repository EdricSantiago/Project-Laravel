<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            // Premi bulanan (default 100.000)
            $table->decimal('asuransi_premium', 15, 2)->default(100000)->after('balance');
            // Status aktif/tidak aktif asuransinya
            $table->boolean('asuransi_aktif')->default(true)->after('asuransi_premium');
            // Tanggal terakhir bayar (untuk cek apakah bulan ini sudah dibayar)
            $table->date('asuransi_last_paid')->nullable()->after('asuransi_aktif');
        });
    }

    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['asuransi_premium', 'asuransi_aktif', 'asuransi_last_paid']);
        });
    }
};
