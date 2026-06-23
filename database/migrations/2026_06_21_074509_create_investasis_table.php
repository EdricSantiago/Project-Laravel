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
        Schema::create('investasi_sahams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('saham_id')->constrained('sahams')->onDelete('cascade');
            $table->unsignedInteger('jumlah_lembar');
            $table->decimal('harga_beli', 15, 2)->comment('Snapshot harga saat transaksi dilakukan');
            $table->date('tanggal_beli');
            $table->enum('status', ['aktif', 'terjual'])->default('aktif');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investasis');
    }
};
