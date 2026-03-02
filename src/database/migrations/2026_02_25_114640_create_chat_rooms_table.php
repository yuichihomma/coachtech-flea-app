<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();

            // どの商品についての取引か
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();

            // 購入希望者（チャット開始者）
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();

            // 出品者（items.user_id からも取れるが、検索/認可を楽にするため保持）
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();

            // 取引状態（まずは最小）
            $table->string('status')->default('trading'); // trading / completed

            $table->timestamps();

            // 同じ商品に対して同じ購入希望者は1ルームのみ
            $table->unique(['item_id', 'buyer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};