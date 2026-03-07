<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{
    public function store(StoreMessageRequest $request, ChatRoom $chatRoom)
    {
        // 認可
        abort_if(!in_array(Auth::id(), [$chatRoom->buyer_id, $chatRoom->seller_id]), 403);

        $data = $request->validated();

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('messages', 'public');
        }

        $payload = [
            'chat_room_id' => $chatRoom->id,
            'user_id'      => Auth::id(),
            'body'         => $data['body'],
        ];

        if ($path) {
            $payload['image_path'] = $path;
        }

        Message::create($payload);

        return back();
    }

    public function update(Request $request, Message $message)
    {
        abort_if($message->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:400'],
        ]);

        $message->update([
            'body' => $data['body'],
        ]);

        return back();
    }

    public function destroy(Message $message)
    {
        abort_if($message->user_id !== Auth::id(), 403);

        $message->delete();

        return back();
    }
}
