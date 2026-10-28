<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->boolean('is_locked')->default(false)->after('last_login_ip');
            $table->timestamp('locked_at')->nullable()->after('is_locked');
            $table->string('locked_reason')->nullable()->after('locked_at');
            $table->index(['is_locked', 'last_login_at']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('description');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_locked', 'last_login_at']);
            $table->dropColumn(['last_login_at', 'last_login_ip', 'is_locked', 'locked_at', 'locked_reason']);
        });
    }
};
