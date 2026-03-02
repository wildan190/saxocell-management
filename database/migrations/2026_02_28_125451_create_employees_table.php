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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_id')->unique();
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowance', 15, 2)->nullable();
            $table->boolean('tax_pph21')->default(false);
            $table->boolean('jht')->default(false);
            $table->boolean('bpjs')->default(false);
            $table->boolean('overtime_eligible')->default(false);
            $table->timestamp('onboarded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
