{{ $chatRoom->seller->name }} 様

購入者が「{{ $item->name }}」の取引を完了しました。

取引チャット：
{{ route('chat.show', $chatRoom) }}

--
COACHTECH Flea