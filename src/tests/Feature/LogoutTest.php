<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    private string $logoutPath = '/logout';

    public function test_user_can_logout(): void
    {
        // ユーザー作成
        $user = User::factory()->create();

        // ログイン状態にする
        $this->actingAs($user);

        // ログアウト実行
        $response = $this->post($this->logoutPath);

        // 未認証になっていることを確認
        $this->assertGuest();

        // リダイレクト確認（多くは / や /login）
        $response->assertStatus(302);
    }
}