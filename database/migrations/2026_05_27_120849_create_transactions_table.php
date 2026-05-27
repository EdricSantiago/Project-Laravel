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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['deposit', 'withdraw', 'transfer']);
            $table->decimal('amount', 15, 2);

            $table->foreignId('sender_id')
                ->nullable()
                ->constrained('accounts')
                ->nullOnDelete();

            $table->foreignId('receiver_id')
                ->nullable()
                ->constrained('accounts')
                ->nullOnDelete();

            $table->enum('status', ['success', 'failed', 'pending'])
                ->default('success');
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
