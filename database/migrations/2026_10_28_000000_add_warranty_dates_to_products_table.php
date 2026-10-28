<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->date('warranty_start_date')->nullable()->after('stock');
            $table->date('warranty_end_date')->nullable()->after('warranty_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['warranty_start_date', 'warranty_end_date']);
        });
    }
};
