<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'created_at']);
            $table->index(['category_id', 'is_active']);
            $table->index(['brand_id', 'is_active']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['products_is_active_created_at_index']);
            $table->dropIndex(['products_category_id_is_active_index']);
            $table->dropIndex(['products_brand_id_is_active_index']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['orders_status_created_at_index']);
            $table->dropIndex(['orders_user_id_created_at_index']);
        });
    }
};
