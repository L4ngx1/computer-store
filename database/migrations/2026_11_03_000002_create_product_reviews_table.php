<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo bảng đánh giá sản phẩm (product_reviews).
     */
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            // Điểm đánh giá từ 1 đến 5 sao
            $table->unsignedTinyInteger('rating');
            $table->text('comment');
            // Chỉ đánh giá đã được duyệt mới hiển thị công khai
            $table->boolean('is_approved')->default(false)->index();
            $table->timestamps();

            // Mỗi người dùng chỉ được đánh giá một sản phẩm một lần
            $table->unique(['user_id', 'product_id']);
            // Truy vấn phổ biến: đánh giá đã duyệt của một sản phẩm, mới nhất trước
            $table->index(['product_id', 'is_approved', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
