<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\Rating;
use App\Mail\TradeCompletedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatRoomController extends Controller
{
    public function store(Item $item)
    {
        $buyerId = auth()->id();
        $sellerId = $item->user_id;

        // 出品者が自分の商品でチャット開始するのは不可（購入希望者開始のルール）
        abort_if($buyerId === $sellerId, 403);

        // 同じ item × buyer は1部屋
        $chatRoom = ChatRoom::firstOrCreate(
            ['item_id' => $item->id, 'buyer_id' => $buyerId],
            ['seller_id' => $sellerId, 'status' => 'trading']
        );

        return redirect()->route('chat.show', $chatRoom);
    }

    public function show(ChatRoom $chatRoom)
    {
        $userId = auth()->id();

        // 認可：buyer or seller のみ
        abort_if(!in_array($userId, [$chatRoom->buyer_id, $chatRoom->seller_id]), 403);

        $chatRoom->load([
            'item',
            'buyer',
            'seller',
            'messages.user',
        ]);

        // 相手が送った未読を、チャットを開いた時点で既読にする
        Message::where('chat_room_id', $chatRoom->id)
            ->where('user_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $chatRoom->messages()->with('user')->get();

        $otherRooms = ChatRoom::with(['item', 'buyer', 'seller'])
            ->where(function ($query) use ($userId) {
                $query->where('buyer_id', $userId)
                    ->orWhere('seller_id', $userId);
            })
            ->where('id', '!=', $chatRoom->id)
            ->latest()
            ->get();

        $hasRated = Rating::where('chat_room_id', $chatRoom->id)
        ->where('rater_id', $userId)
        ->exists();

        return view('chat.show', compact('chatRoom', 'otherRooms', 'hasRated'));
    }

    public function trading()
{
    $userId = auth()->id();

    // 自分が buyer（購入希望者）として参加している取引
    $asBuyer = ChatRoom::with(['item'])
        ->where('buyer_id', $userId)
        ->latest()
        ->get();

    // 自分が seller（出品者）として参加している取引
    $asSeller = ChatRoom::with(['item'])
        ->where('seller_id', $userId)
        ->latest()
        ->get();

    // 商品一覧として見せたいならここでまとめる（ひとまず2配列でOK）
    return view('mypage.trading', compact('asBuyer', 'asSeller'));
}

public function start(Item $item)
    {
        $user = Auth::user();

        // 自分の出品物には開始できない
        if ($item->user_id === $user->id) {
            return back();
        }

        $room = ChatRoom::firstOrCreate(
            [
                'item_id'  => $item->id,
                'buyer_id' => $user->id,
            ],
            [
                'seller_id' => $item->user_id,
                'status'    => 'trading',
            ]
        );

        return redirect()->route('chat.show', $room);
    }

    public function complete(ChatRoom $chatRoom)
{
    $userId = auth()->id();

    // 購入者のみ
    abort_if((int)$chatRoom->buyer_id !== (int)$userId, 403, '購入者のみ取引完了できます');

    // trading 以外ならそのまま戻す（rating/completed）
    if ($chatRoom->status !== 'trading') {
        return redirect()->route('chat.show', $chatRoom);
    }

    // trading → rating（評価待ち）
    $chatRoom->update([
        'status' => 'rating',
        'completed_at' => now(),
    ]);

    $chatRoom->load('seller');

    $notificationMailer = config('mail.notification_mailer');

    if ($notificationMailer) {
        Mail::mailer($notificationMailer)
            ->to($chatRoom->seller->email)
            ->send(new TradeCompletedMail($chatRoom));
    } else {
        Mail::to($chatRoom->seller->email)->send(new TradeCompletedMail($chatRoom));
    }

    return redirect()->route('chat.show', $chatRoom);
}

}
