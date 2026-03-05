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
        Schema::table('warehouse_store_transfers', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('transfer_date'); // pending, shipping, arrived, received, rejected
            $table->string('shipping_number')->nullable()->after('status');
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('shipping_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_store_transfers', function (Blueprint $table) {
            $table->dropColumn(['status', 'shipping_number', 'shipping_cost']);
        });
    }
};
