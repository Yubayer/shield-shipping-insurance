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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('name')->nullable();
            $table->string('order_number')->nullable();
            $table->json('data')->nullable();
            $table->json('line_items')->nullable();
            $table->tinyInteger('order_status')->default(1)->nullable();
            $table->boolean('protection_status')->default(0)->nullable();
            $table->decimal('total_price', 8, 2)->nullable();
            $table->decimal('protection_price', 8, 2)->nullable();
            $table->decimal('subtotal_price', 8, 2)->nullable();
            $table->decimal('total_tax', 8, 2)->nullable();
            $table->decimal('total_discounts', 8, 2)->nullable();
            $table->string('admin_graphql_api_id')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
