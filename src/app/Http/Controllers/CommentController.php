<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {
        //  未ログイン時のコメント送信は弾く
        if (!Auth::check()) {
            return redirect()->route('items.show', $item->id);
        }

        //  ログイン済のみ保存
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'comment' => $request->content,
        ]);

        return redirect()->route('items.show', $item->id);
    }
}
