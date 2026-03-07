<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, ChatRoom $chatRoom)
    {
        $userId = Auth::id();

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
        ]);

        // 相手（評価される人）
        $rateeId = ((int)$chatRoom->buyer_id === (int)$userId)
            ? $chatRoom->seller_id
            : $chatRoom->buyer_id;

        // 同じ人が同じ部屋で複数回評価しない
        Rating::updateOrCreate(
            ['chat_room_id' => $chatRoom->id, 'rater_id' => $userId],
            ['ratee_id' => $rateeId, 'rating' => $data['rating']]
        );

        // 両者の評価が揃ったら completed
        $buyerRated  = Rating::where('chat_room_id', $chatRoom->id)
            ->where('rater_id', $chatRoom->buyer_id)
            ->exists();

        $sellerRated = Rating::where('chat_room_id', $chatRoom->id)
            ->where('rater_id', $chatRoom->seller_id)
            ->exists();

        if ($buyerRated && $sellerRated) {
            $chatRoom->update(['status' => 'completed']);
        }

        return redirect()
            ->route('items.index')
            ->with('rating_success', true);
    }
}
