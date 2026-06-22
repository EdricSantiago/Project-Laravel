<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecommerce_products', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['pulsa', 'token_listrik', 'air']);
            $table->string('provider');
            $table->string('name');
            $table->unsignedBigInteger('nominal');
            $table->unsignedBigInteger('price');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecommerce_products');
    }
};