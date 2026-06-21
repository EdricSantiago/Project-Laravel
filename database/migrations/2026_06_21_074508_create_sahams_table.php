<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sahams', function (Blueprint $table) {
            $table->id();
            $table->string('kode_saham', 10)->unique();
            $table->string('nama_perusahaan');
            $table->string('sektor')->nullable();
            $table->decimal('harga_saat_ini', 15, 2);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sahams');
    }
};
