<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('store_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('finance_transaction_id')->nullable()->constrained()->nullOnDelete();

            $table->string('activity_type'); // repair, improvement, price_adjustment, status_change, other
            $table->text('description');
            $table->decimal('cost', 15, 2)->default(0);

            // For price adjustments specifically
            $table->decimal('old_purchase_price', 15, 2)->nullable();
            $table->decimal('new_purchase_price', 15, 2)->nullable();
            $table->decimal('old_selling_price', 15, 2)->nullable();
            $table->decimal('new_selling_price', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_activities');
    }
};
