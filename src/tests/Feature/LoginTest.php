<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private string $loginPath = '/login';

    public function test_email_is_required(): void
    {
        $response = $this->from($this->loginPath)->post($this->loginPath, [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertRedirect($this->loginPath);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_password_is_required(): void
    {
        $response = $this->from($this->loginPath)->post($this->loginPath, [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertRedirect($this->loginPath);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        // 既存ユーザー（正しいパスワードは password123）
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 間違ったパスワードでログイン
        $response = $this->from($this->loginPath)->post($this->loginPath, [
            'email' => 'test@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertRedirect($this->loginPath);

        // 実装によってエラーキーは変わるので「何かしらエラーがある」までに留める（堅い）
        $response->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post($this->loginPath, [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertStatus(302);
    }
}