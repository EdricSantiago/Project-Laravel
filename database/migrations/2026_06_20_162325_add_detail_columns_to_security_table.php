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
        Schema::table('security', function (Blueprint $table) {
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable();
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();  
            $table->string('status')->default('success');
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security', function (Blueprint $table) {
            $table->dropColumn(['user_agent', 'device_type', 'old_value', 'new_value', 'status', 'notes']);
        });
    }
};
