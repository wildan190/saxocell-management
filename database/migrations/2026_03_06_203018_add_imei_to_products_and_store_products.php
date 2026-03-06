<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('imei')->nullable()->after('sku');
        });

        Schema::table('store_products', function (Blueprint $table) {
            $table->string('imei')->nullable()->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('imei');
        });

        Schema::table('store_products', function (Blueprint $table) {
            $table->dropColumn('imei');
        });
    }
};
