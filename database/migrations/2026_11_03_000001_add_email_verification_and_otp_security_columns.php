<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bổ sung cột xác minh email cho người dùng và các cột bảo mật cho OTP.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Thời điểm xác minh email (null = chưa xác minh)
            $table->timestamp('email_verified_at')->nullable()->after('password');
            $table->index('email_verified_at');
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            // Số lần nhập sai OTP liên tiếp (khóa OTP khi vượt ngưỡng)
            $table->unsignedInteger('attempts')->default(0)->after('token');
        });
    }

    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropColumn('attempts');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email_verified_at']);
            $table->dropColumn('email_verified_at');
        });
    }
};
