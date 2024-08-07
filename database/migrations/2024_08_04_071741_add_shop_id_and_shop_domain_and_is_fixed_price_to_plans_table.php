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
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id')->nullable()->after('id');
            $table->string('shop_domain')->nullable()->after('shop_id');
            $table->boolean('is_fixed_plan')->default(0)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('shop_id');
            $table->dropColumn('shop_domain');
            $table->dropColumn('is_fixed_price');
        });
    }
};
