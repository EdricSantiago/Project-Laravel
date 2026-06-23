<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('ecommerce_orders', 'transaction_id')) {
            Schema::table('ecommerce_orders', function (Blueprint $table) {
                $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('ecommerce_orders', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropColumn('transaction_id');
        });
    }
};