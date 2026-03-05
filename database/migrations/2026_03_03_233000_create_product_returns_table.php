<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('resi')->nullable();
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->string('status')->default('pending'); // pending, shipped, arrived, cleared
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('product_return_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_return_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->foreignId('finance_account_id')->nullable()->constrained()->onDelete('set null');
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_return_payments');
        Schema::dropIfExists('product_returns');
    }
};
