<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('quality_label')->default('none')->after('status'); // none (sesuai), yellow (kurang sesuai), red (tidak sesuai)
            $table->text('quality_description')->nullable()->after('quality_label');
            $table->string('store_label')->default('none')->after('quality_description'); // none, grey, red
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['quality_label', 'quality_description', 'store_label']);
        });
    }
};
