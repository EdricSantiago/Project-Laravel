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
            $table->string('pin',70)->nullable()->comment('PIN transaksi terenkripsi');
            $table->integer('failed_pin_attempts')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions'); 
    }

    
};