<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('Quên mật khẩu');
    }

    public function test_reset_code_can_be_requested(): void
    {
        Mail::fake();

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect('/reset-password?email=test%40example.com');
        $response->assertSessionHas('success');

        // Kiểm tra OTP được lưu (dạng hash)
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);

        $tokenRecord = DB::table('password_reset_tokens')->where('email', 'test@example.com')->first();
        $this->assertNotNull($tokenRecord);
        // OTP được lưu dạng hash, nên độ dài sẽ là 60 (bcrypt hash)
        $this->assertEquals(60, strlen($tokenRecord->token));
    }

    public function test_password_can_be_reset_with_valid_otp(): void
    {
        $otp = '123456';

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('old-password'),
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => Hash::make($otp),
            'attempts' => 0,
            'created_at' => now(),
        ]);

        $response = $this->post('/reset-password', [
            'email' => 'test@example.com',
            'code' => $otp,
            'password' => 'NewSecure123',
            'password_confirmation' => 'NewSecure123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');

        // Kiểm tra mật khẩu đã thay đổi
        $user->refresh();
        $this->assertTrue(Hash::check('NewSecure123', $user->password));

        // OTP phải bị xóa sau khi đổi mật khẩu thành công
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_password_cannot_be_reset_with_invalid_otp(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('old-password'),
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => Hash::make('123456'),
            'attempts' => 0,
            'created_at' => now(),
        ]);

        $response = $this->post('/reset-password', [
            'email' => 'test@example.com',
            'code' => '000000', // OTP sai
            'password' => 'NewSecure123',
            'password_confirmation' => 'NewSecure123',
        ]);

        $response->assertSessionHasErrors(['code']);

        $user->refresh();
        $this->assertFalse(Hash::check('NewSecure123', $user->password));
    }
}
