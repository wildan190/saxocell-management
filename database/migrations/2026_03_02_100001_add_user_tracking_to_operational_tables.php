<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Goods Receipts (Warehouse): who received the delivery
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->string('received_by')->nullable()->after('sender_name');
        });

        // Store Goods Requests: full lifecycle tracking
        Schema::table('store_goods_requests', function (Blueprint $table) {
            $table->string('requested_by')->nullable()->after('notes');
            $table->string('confirmed_by')->nullable()->after('requested_by');
            $table->string('shipped_by')->nullable()->after('confirmed_by');
            $table->string('received_by')->nullable()->after('shipped_by');
        });

        // Store-to-Store Transfers: full lifecycle tracking
        Schema::table('store_to_store_transfers', function (Blueprint $table) {
            $table->string('created_by')->nullable()->after('notes');
            $table->string('shipped_by')->nullable()->after('created_by');
            $table->string('received_by')->nullable()->after('shipped_by');
        });
    }

    public function down(): void
    {
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->dropColumn('received_by');
        });

        Schema::table('store_goods_requests', function (Blueprint $table) {
            $table->dropColumn(['requested_by', 'confirmed_by', 'shipped_by', 'received_by']);
        });

        Schema::table('store_to_store_transfers', function (Blueprint $table) {
            $table->dropColumn(['created_by', 'shipped_by', 'received_by']);
        });
    }
};
