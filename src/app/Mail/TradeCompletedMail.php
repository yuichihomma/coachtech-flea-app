<?php

namespace App\Mail;

use App\Models\ChatRoom;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TradeCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ChatRoom $chatRoom) {}

    public function build()
    {
        $item = $this->chatRoom->item;

        return $this->subject('【COACHTECH】取引完了のお知らせ')
            ->view('emails.trade_completed')
            ->with([
                'chatRoom' => $this->chatRoom,
                'item' => $item,
            ]);
    }
}