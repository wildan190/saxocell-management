<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trade_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->string('trade_in_number')->unique();
            $table->string('customer_name');
            $table->string('customer_contact')->nullable();
            $table->string('device_name');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('imei')->nullable();
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->text('condition_notes')->nullable();
            $table->string('desired_product')->nullable();
            $table->text('desired_product_notes')->nullable();
            $table->decimal('assessed_value', 15, 2)->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->string('handled_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_ins');
    }
};
