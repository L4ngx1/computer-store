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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Cho phép mua hàng không cần đăng nhập nếu muốn

            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->text('note')->nullable();

            $table->decimal('total_amount', 15, 2);
            $table->string('payment_method')->default('COD'); // COD, VNPAY, MOMO

            // Trạng thái đơn hàng (quản lý ở Admin)
            // Các giá trị: pending (chờ duyệt), processing (đang xử lý), shipping (đang giao), completed (đã giao), cancelled (đã hủy)
            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
