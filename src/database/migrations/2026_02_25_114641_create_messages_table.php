<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chat_room_id')->constrained('chat_rooms')->cascadeOnDelete();

            // 送信者（buyer または seller）
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->text('body');

            $table->timestamps();

            // よく使う取得用（チャット表示が速くなる）
            $table->index(['chat_room_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};