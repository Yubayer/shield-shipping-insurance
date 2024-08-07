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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->string('domain')->nullable();
            $table->json('data')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('primary_location_id')->nullable();
            $table->string('admin_graphql_api_id')->nullable();
            $table->string('app_url')->nullable()->default(env('APP_URL', 'http://hello'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
