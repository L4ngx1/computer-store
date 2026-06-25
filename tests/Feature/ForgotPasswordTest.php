<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
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

        // Check if OTP is saved in database
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);

        $tokenRecord = DB::table('password_reset_tokens')->where('email', 'test@example.com')->first();
        $this->assertNotNull($tokenRecord);
        $this->assertEquals(6, strlen($tokenRecord->token));

        Mail::assertSent(ResetPasswordMail::class, function ($mail) use ($tokenRecord) {
            return $mail->code === $tokenRecord->token && $mail->hasTo('test@example.com');
        });
    }

    public function test_password_can_be_reset_with_valid_otp(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('old-password'),
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => '123456',
            'created_at' => now(),
        ]);

        $response = $this->post('/reset-password', [
            'email' => 'test@example.com',
            'code' => '123456',
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');

        // Verify password was changed
        $user->refresh();
        $this->assertTrue(Hash::check('new-secure-password', $user->password));

        // Token should be deleted
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
            'token' => '123456',
            'created_at' => now(),
        ]);

        $response = $this->post('/reset-password', [
            'email' => 'test@example.com',
            'code' => '000000', // invalid code
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        $response->assertSessionHasErrors(['code']);
        $user->refresh();
        $this->assertFalse(Hash::check('new-secure-password', $user->password));
    }
}
