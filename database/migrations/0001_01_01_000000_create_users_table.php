<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('no_hp', 20)->unique();
            $table->string('nik', 16)->unique()->comment('Nomor Induk Kependudukan');
            $table->string('account_number', 16)->unique()->nullable();
            $table->string('password');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('inactive');
            $table->string('pin', 6)->nullable()->comment('PIN transaksi terenkripsi');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};