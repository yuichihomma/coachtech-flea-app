<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // ① 先にユーザーを1人作る（id=1 が必ず作られる）
    User::factory()->create();

    // ② その後に商品・カテゴリ系を作る
    $this->call([
        CategorySeeder::class,
        ItemSeeder::class,
        CategoryItemSeeder::class,
    ]);
    }
}
