<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique(); // Mã định danh sản phẩm (Ví dụ: LAP-ASUS-001)
            $table->text('summary')->nullable(); // Mô tả ngắn
            $table->longText('description')->nullable(); // Mô tả chi tiết

            $table->decimal('price', 15, 2); // Giá gốc
            $table->decimal('sale_price', 15, 2)->nullable(); // Giá khuyến mãi

            $table->integer('stock')->default(0); 
            $table->string('thumbnail'); 
            $table->boolean('is_featured')->default(false); // Sản phẩm nổi bật
            $table->boolean('is_active')->default(true); // Trạng thái bán/ngừng bán

            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
