<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private string $registerPath = '/register';

    public function test_name_is_required(): void
    {
        $response = $this->from($this->registerPath)->post($this->registerPath, [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect($this->registerPath);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_email_is_required(): void
    {
        $response = $this->from($this->registerPath)->post($this->registerPath, [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect($this->registerPath);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_password_is_required(): void
    {
        $response = $this->from($this->registerPath)->post($this->registerPath, [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect($this->registerPath);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_password_must_be_at_least_8_characters(): void
    {
        $response = $this->from($this->registerPath)->post($this->registerPath, [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertRedirect($this->registerPath);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_password_confirmation_must_match(): void
    {
        $response = $this->from($this->registerPath)->post($this->registerPath, [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertRedirect($this->registerPath);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_user_can_register_successfully(): void
    {
        $response = $this->post($this->registerPath, [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $this->assertAuthenticated();
        $response->assertStatus(302);
    }
}