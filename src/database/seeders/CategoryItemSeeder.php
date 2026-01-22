<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('category_item')->insert([
            // 腕時計 → ファッション, アクセサリー, メンズ
            ['item_id' => 1, 'category_id' => 1],
            ['item_id' => 1, 'category_id' => 12],
            ['item_id' => 1, 'category_id' => 5],

            // HDD → 家電
            ['item_id' => 2, 'category_id' => 2],
            ['item_id' => 2, 'category_id' => 3],

            // 玉ねぎ → キッチン
            ['item_id' => 3, 'category_id' => 10],

            // 革靴 → ファッション
            ['item_id' => 4, 'category_id' => 1],
            ['item_id' => 4, 'category_id' => 5],
            ['item_id' => 4, 'category_id' => 11],

            // ノートPC → 家電
            ['item_id' => 5, 'category_id' => 2],
            ['item_id' => 5, 'category_id' => 3],
            ['item_id' => 5, 'category_id' => 8],
            ['item_id' => 5, 'category_id' => 9],

            // マイク → 家電
            ['item_id' => 6, 'category_id' => 2],

            // ショルダーバッグ → ファッション
            ['item_id' => 7, 'category_id' => 1],
            ['item_id' => 7, 'category_id' => 4],
            ['item_id' => 7, 'category_id' => 11],
            ['item_id' => 7, 'category_id' => 12],

            // タンブラー → キッチン
            ['item_id' => 8, 'category_id' => 10],
            ['item_id' => 8, 'category_id' => 12],

            // コーヒーミル → キッチン
            ['item_id' => 9, 'category_id' => 10],
            ['item_id' => 9, 'category_id' => 3],

            //メイクセット → コスメ
            ['item_id' => 10, 'category_id' => 6],
        ]);
    }
}
