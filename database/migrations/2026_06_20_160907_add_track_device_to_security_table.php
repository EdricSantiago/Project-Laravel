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
        $table->text('user_ip')->nullable()->after('ip_address');
        $table->string('device_type')->nullable()->after('device'); 
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security', function (Blueprint $table) {
            $table->dropColumn(['user_ip', 'device_type']);
        });
    }
};
