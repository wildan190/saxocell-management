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
        Schema::table('pos_transactions', function (Blueprint $table) {
            $table->boolean('is_trade_in')->default(false)->after('payment_method');
            $table->string('trade_in_device_name')->nullable()->after('is_trade_in');
            $table->string('trade_in_imei')->nullable()->after('trade_in_device_name');
            $table->decimal('trade_in_customer_price', 15, 2)->nullable()->after('trade_in_imei');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_transactions', function (Blueprint $table) {
            $table->dropColumn(['is_trade_in', 'trade_in_device_name', 'trade_in_imei', 'trade_in_customer_price']);
        });
    }
};
