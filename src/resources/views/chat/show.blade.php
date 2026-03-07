@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Str;

    $currentUserId = Auth::id();
    $currentRoom = $chatRoom;
    $partner = $currentRoom->buyer_id === $currentUserId ? $currentRoom->seller : $currentRoom->buyer;
    $isBuyer = (int) $currentRoom->buyer_id === (int) $currentUserId;

    $resolveImageUrl = function (?string $path) {
        if (!$path) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : asset('storage/' . $path);
    };

    $defaultUserImg = asset('images/default-user.png');
    $userIconUrl = function ($user) use ($resolveImageUrl, $defaultUserImg) {
        return $resolveImageUrl($user->image ?? null) ?? $defaultUserImg;
    };

    $currentItemImageUrl = $resolveImageUrl($currentRoom->item->img_url ?? $currentRoom->item->image_path ?? null);
    $partnerIconUrl = $userIconUrl($partner);
@endphp

<div class="trade-chat-page">
    {{-- 左：サイドバー --}}
    <aside class="trade-sidebar">
        <div class="trade-sidebar__title">その他の取引</div>

        @if (!empty($otherRooms) && $otherRooms->isNotEmpty())
            <nav class="trade-list">
                @foreach ($otherRooms as $sidebarRoom)
                    @php
                        $otherUser = $sidebarRoom->buyer_id === $currentUserId ? $sidebarRoom->seller : $sidebarRoom->buyer;
                        $sidebarRoomImageUrl = $resolveImageUrl($sidebarRoom->item->img_url ?? $sidebarRoom->item->image_path ?? null);
                    @endphp

                    <a class="trade-list__item" href="{{ route('chat.show', $sidebarRoom) }}">
                        <div class="trade-list__thumb">
                            @if ($sidebarRoomImageUrl)
                                <img src="{{ $sidebarRoomImageUrl }}" alt="{{ $sidebarRoom->item->name ?? '商品画像' }}" class="trade-list__thumb-image">
                            @endif
                        </div>
                        <div class="trade-list__meta">
                            <div class="trade-list__name">{{ $sidebarRoom->item->name ?? '商品名' }}</div>
                            <div class="trade-list__sub">{{ $otherUser->name ?? 'ユーザー名' }}</div>
                        </div>
                    </a>
                @endforeach
            </nav>
        @endif
    </aside>

    {{-- 右：メイン --}}
    <main class="trade-main">
        @if (session('success'))
            <p class="flash-success">{{ session('success') }}</p>
        @endif
        @if (session('rating_success'))
            <div class="flash-toast flash-toast--success">
                評価を送信しました！
            </div>
        @endif

        {{-- ヘッダー --}}
        <header class="trade-header">
            <div class="trade-header__left">
                <div class="trade-user">
                    <div class="trade-user__avatar">
                        @if ($partnerIconUrl)
                            <img src="{{ $partnerIconUrl }}" alt="{{ $partner->name ?? 'ユーザー' }}" class="trade-user__avatar-image">
                        @endif
                    </div>
                    <div class="trade-user__text">
                        <div class="trade-user__title">「{{ $partner->name ?? 'ユーザー名' }}」さんとの取引画面</div>
                    </div>
                </div>
            </div>

            <div class="trade-header__right">
                @if ($currentRoom->status === 'completed')
                    <div class="trade-completed-badge">取引完了</div>
                @elseif ($currentRoom->status === 'rating')
                    <div class="trade-wait">評価待ち</div>
                @elseif ($isBuyer && $currentRoom->status === 'trading')
                    <form action="{{ route('chat.complete', $currentRoom) }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="trade-complete-btn"
                            onclick="return confirm('取引を完了しますか？（取り消しはできません）')"
                        >
                            取引を完了する
                        </button>
                    </form>
                @endif
            </div>
        </header>

        {{-- 商品情報 --}}
        <section class="trade-item">
            <div class="trade-item__thumb">
                @if ($currentItemImageUrl)
                    <img src="{{ $currentItemImageUrl }}" alt="{{ $currentRoom->item->name ?? '商品画像' }}" class="trade-item__thumb-image">
                @else
                    商品画像
                @endif
            </div>
            <div class="trade-item__info">
                <div class="trade-item__name">{{ $currentRoom->item->name ?? '商品名' }}</div>
                <div class="trade-item__price">¥{{ number_format($currentRoom->item->price ?? 0) }}</div>
            </div>
        </section>

        {{-- メッセージ一覧 --}}
        <section class="trade-messages">
            @forelse ($currentRoom->messages->sortBy('created_at') as $message)
                @php
                    $isMine = $message->user_id === $currentUserId;
                    $messageUserIconUrl = $userIconUrl($message->user);
                @endphp

                <div class="msg {{ $isMine ? 'msg--me' : 'msg--other' }}">
                    @unless ($isMine)
                        <div class="msg__avatar">
                            <img src="{{ $messageUserIconUrl }}" alt="{{ $message->user->name ?? 'ユーザー' }}" class="msg__avatar-image">
                        </div>
                    @endunless

                    <div class="msg__body">
                        <div class="msg__name {{ $isMine ? 'msg__name--me' : '' }}">
                            {{ $message->user->name ?? 'ユーザー名' }}
                        </div>
                        {{-- 表示用 --}}
<div class="msg__bubble" id="body-display-{{ $message->id }}">
    {{ $message->body }}
</div>

@if (!empty($message->image_path))
    <div class="msg__image">
        <img src="{{ asset('storage/' . $message->image_path) }}" alt="添付画像" class="msg__image-content">
    </div>
@endif

{{-- 編集フォーム（最初は非表示） --}}
<form method="POST"
      action="{{ route('messages.update', $message) }}"
      id="edit-form-{{ $message->id }}"
      class="msg__edit"
      style="display:none;">
    @csrf
    @method('PATCH')

    <input type="text"
           name="body"
           value="{{ $message->body }}"
           class="msg__edit-input">

    <div class="msg__edit-actions">
        <button type="submit" class="msg__action">保存</button>
        <button type="button" class="msg__action js-cancel-edit" data-message-id="{{ $message->id }}">キャンセル</button>
    </div>
</form>

@if ($isMine)
    <div class="msg__actions">
        {{-- 編集 --}}
        <button type="button"
                class="msg__action js-toggle-edit"
                data-message-id="{{ $message->id }}">
            編集
        </button>

        {{-- 削除 --}}
        <form method="POST"
              action="{{ route('messages.destroy', $message) }}"
              style="display:inline;"
              onsubmit="return confirm('削除しますか？')">
            @csrf
            @method('DELETE')
            <button type="submit" class="msg__action msg__action--danger">
                削除
            </button>
        </form>
    </div>
@endif

                    </div>

                    @if ($isMine)
                        <div class="msg__avatar">
                            <img src="{{ $messageUserIconUrl }}" alt="{{ $message->user->name ?? 'ユーザー' }}" class="msg__avatar-image">
                        </div>
                    @endif

                </div>
            @empty
                <p class="msg__empty">まだメッセージはありません。</p>
            @endforelse
        </section>

        {{-- 入力フォーム --}}
        <footer class="trade-input">
            <form class="trade-input__form" method="POST" action="{{ route('messages.store', $currentRoom) }}" enctype="multipart/form-data" data-room-id="{{ $currentRoom->id }}">
                @csrf

                <input
                    type="text"
                    name="body"
                    id="tradeMessageBody"
                    class="trade-input__text"
                    placeholder="取引メッセージを記入してください"
                    value="{{ old('body') }}"
                >

                <input
                    type="file"
                    name="image"
                    id="messageImage"
                    style="display:none;"
                >

                <button type="button" class="trade-input__file" id="imagePickBtn">画像を追加
                </button>

                <button type="submit" class="trade-input__send" aria-label="送信">
                    ➤
                </button>
            </form>

            @error('body')
                <p class="trade-input__error">{{ $message }}</p>
            @enderror
            @error('image')
                <p class="trade-input__error">{{ $message }}</p>
            @enderror
        </footer>
    </main>
</div>

@if ($currentRoom->status === 'rating' && !$hasRated)
    {{-- 評価モーダル表示（購入者も出品者も） --}}
    <div class="rating-modal">
        <div class="rating-modal__overlay"></div>

        <div class="rating-modal__panel">
            <div class="rating-modal__title">取引が完了しました。</div>
            <div class="rating-modal__text">今回の取引相手はどうでしたか？</div>

            <form method="POST" action="{{ route('ratings.store', $chatRoom) }}">
                @csrf
                <input type="hidden" name="rating" id="ratingValue" value="0">

                <div class="rating-stars" id="ratingStars">
                    @for ($i = 1; $i <= 5; $i++)
                        <button type="button" class="star" data-value="{{ $i }}">★</button>
                    @endfor
                </div>

                <button type="submit" class="rating-modal__submit">送信する</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stars = document.querySelectorAll('#ratingStars .star');
            const input = document.getElementById('ratingValue');

            const set = (n) => {
                input.value = n;
                stars.forEach(s => s.classList.toggle('is-on', Number(s.dataset.value) <= n));
            };

            stars.forEach(s => s.addEventListener('click', () => set(Number(s.dataset.value))));
        });
    </script>
@endif
<script>
function toggleEdit(id){
    const display = document.getElementById('body-display-' + id);
    const form = document.getElementById('edit-form-' + id);

    if (!display || !form) return;

    display.style.display = 'none';
    form.style.display = 'block';

    // 入力にフォーカス
    const input = form.querySelector('input[name="body"]');
    if (input) input.focus();
}

function cancelEdit(id){
    const display = document.getElementById('body-display-' + id);
    const form = document.getElementById('edit-form-' + id);

    if (!display || !form) return;

    form.style.display = 'none';
    display.style.display = 'block';
}

document.addEventListener('click', (e) => {
    const toggleButton = e.target.closest('.js-toggle-edit');
    if (toggleButton) {
        const id = Number(toggleButton.dataset.messageId);
        if (Number.isInteger(id)) {
            toggleEdit(id);
        }
        return;
    }

    const cancelButton = e.target.closest('.js-cancel-edit');
    if (!cancelButton) return;

    const id = Number(cancelButton.dataset.messageId);
    if (Number.isInteger(id)) {
        cancelEdit(id);
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('imagePickBtn');
  const fileInput = document.getElementById('messageImage');
  const form = document.querySelector('.trade-input__form');

  if (!btn || !fileInput || !form) return;

  const MAX_BYTES = 1 * 2048 * 2048; // 2MB
  const MAX_MB_TEXT = '2MB';

  const rejectLargeFile = () => {
    alert(`画像は${MAX_MB_TEXT}以下のみアップロードできます。`);
    fileInput.value = '';
    btn.textContent = '画像を追加';
  };

  btn.addEventListener('click', () => fileInput.click());

  fileInput.addEventListener('change', () => {
    const file = fileInput.files?.[0];

    if (!file) {
      btn.textContent = '画像を追加';
      return;
    }

    if (file.size > MAX_BYTES) {
      rejectLargeFile();
      return;
    }

    btn.textContent = file.name;
  });

  form.addEventListener('submit', (e) => {
    const file = fileInput.files?.[0];
    if (file && file.size > MAX_BYTES) {
      e.preventDefault();
      rejectLargeFile();
    }
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const bodyInput = document.getElementById('tradeMessageBody');
  const form = document.querySelector('.trade-input__form');

  if (!bodyInput || !form) return;

  const roomId = form.dataset.roomId;
  const storageKey = `trade-message-draft-${roomId}`;

  // old('body') がなければ、保存済み下書きを復元
  if (!bodyInput.value) {
    const savedDraft = sessionStorage.getItem(storageKey);
    if (savedDraft !== null) {
      bodyInput.value = savedDraft;
    }
  }

  // 入力のたびに保存
  bodyInput.addEventListener('input', () => {
    sessionStorage.setItem(storageKey, bodyInput.value);
  });

  // 送信時は下書きを削除
  form.addEventListener('submit', () => {
    sessionStorage.removeItem(storageKey);
  });
});
</script>


@endsection
