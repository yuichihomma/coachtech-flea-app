<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Item::create([
            'name' => '腕時計',
            'price' => 15000,
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'condition' => '良好',
        ]);
        
        Item::create([
            'name' => 'HDD',
            'price' => 5000,
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);
        
        Item::create([
            'name' => '玉ねぎ3束',
            'price' => 300,
            'brand' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'condition' => 'やや傷や汚れあり',
        ]);
        
        Item::create([
            'name' => '革靴',
            'price' => 4000,
            'brand' => 'なし',
            'description' => 'クラシックなデザインの革靴',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'condition' => '状態が悪い',
        ]);
        
        Item::create([
            'name' => 'ノートPC',
            'price' => 45000,
            'brand' => 'なし',
            'description' => '高性能なノートパソコン',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            'condition' => '良好',
        ]);
        
        Item::create([
            'name' => 'マイク',
            'price' => 8000,
            'brand' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);
        
        Item::create([
            'name' => 'ショルダーバック',
            'price' => 3500,
            'brand' => 'なし',
            'description' => 'おしゃれなショルダーバッグ',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            'condition' => 'やや傷や汚れあり',
        ]);
        
        Item::create([
            'name' => 'タンブラー',
            'price' => 500,
            'brand' => 'なし',
            'description' => '使いやすいタンブラー',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            'condition' => '状態が悪い',
        ]);
        Item::create([
            'name' => 'コーヒーミル',
            'price' => 4000,
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            'condition' => '良好',
        ]);
        Item::create([
            'name' => 'メイクセット',
            'price' => 2500,
            'brand' => 'なし',
            'description' => '便利なメイクアップセット',
            'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            'condition' => '目立った傷や汚れなし',
        ]);

    }
}
