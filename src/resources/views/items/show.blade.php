@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
@php
    use Illuminate\Support\Str;

    // 画像URLをここで正規化
    if ($item->img_url) {
        $imageUrl = Str::startsWith($item->img_url, 'http')
            ? $item->img_url
            : asset('storage/' . $item->img_url);
    } else {
        $imageUrl = null;
    }
@endphp

<div class="item-show">
    <div class="item-show__container">

        <!-- 左：商品画像 -->
        <div class="item-show__image">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $item->name }}">
            @else
                <div class="item-show__image--placeholder">商品画像</div>
            @endif
        </div>

        <!-- 右：商品情報 -->
        <div class="item-show__info">

            <h1 class="item-show__name">{{ $item->name }}</h1>

            <p class="item-show__brand">
                {{ $item->brand ?? 'ブランド不明' }}
            </p>

            <p class="item-show__price">
                ¥{{ number_format($item->price) }}
                <span>(税込)</span>
            </p>

            <!-- アイコン群 -->
            <div class="item-icons">

                <!-- いいね -->
                <form action="{{ route('item.like', $item) }}" method="POST">
                    @csrf
                    <button type="submit" class="icon-btn">
                        <img
                            src="{{ asset('images/' . ($isLiked ? 'heart-pink.png' : 'heart-default.png')) }}"
                            alt="like"
                            class="icon"
                        >
                        <span class="icon-count">{{ $item->likes_count ?? 0 }}</span>
                    </button>
                </form>

                <!-- コメント数 -->
                <div class="icon-btn">
                    <img src="{{ asset('images/comment.png') }}" alt="comment" class="icon">
                    <span class="icon-count">{{ $item->comments_count ?? 0 }}</span>
                </div>
            </div>

            <!-- 購入ボタン -->
            <a href="{{ route('purchase.show', $item->id) }}" class="purchase-btn">
                購入手続きへ
            </a>

            <!-- 商品説明 -->
            <section class="item-show__section">
                <h2>商品説明</h2>
                <p>{{ $item->description }}</p>
            </section>

            <!-- 商品情報 -->
            <section class="item-show__section">
                <h2>商品の情報</h2>
                <ul class="item-show__meta">
                    <li>
                        <span class="label">カテゴリー</span>

                        <div class="category-tags">
                        @forelse ($item->categories as $category)
                            <span class="category-tag">{{ $category->name }}</span>
                        @empty
                            <span class="value">未設定</span>
                        @endforelse
                        </div>
                    </li>
                    <li>
                        <span class="label">商品の状態</span>
                        <span class="value">{{ $item->condition ?? '未設定' }}</span>
                    </li>
                </ul>
            </section>

            <!-- コメント一覧 -->
            <section class="item-show__section">
                <h2>コメント ({{ $item->comments_count }})</h2>

                @if($item->comments_count === 0)
                    <p class="item-show__comment-empty">まだコメントはありません</p>
                @else
                    <div class="item-show__comments">
                        @foreach($item->comments as $comment)
                            <div class="item-show__comment">
                                <div class="item-show__comment-icon">
                                    @if ($comment->user && $comment->user->image)
                                        <img
                                            src="{{ asset('storage/' . $comment->user->image) }}"
                                            alt="user icon"
                                            class="comment-user-icon"
                                        >
                                    @else
                                        <div class="comment-user-icon default-icon"></div>
                                    @endif
                                </div>


                                <div class="item-show__comment-body">
                                    <p class="item-show__comment-user">
                                        {{ $comment->user->name ?? '匿名ユーザー' }}
                                    </p>
                                    <p class="item-show__comment-text">
                                        {{ $comment->comment }}
                                    </p>
                                    <span class="item-show__comment-date">
                                        {{ $comment->created_at->format('Y/m/d H:i') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- コメント投稿 -->
            <section class="item-show__section">
                <h2>商品のコメント</h2>

                @if ($errors->any())
                    <div class="item-show__error">
                        @foreach ($errors->all() as $error)
                            <p class="item-show__error-text">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('comments.store', $item->id) }}" method="POST">
                    @csrf
                    <textarea
                        name="content"
                        class="item-show__comment-textarea"
                        placeholder="こちらにコメントが入ります"
                        required
                    >{{ old('content') }}</textarea>

                    <button type="submit" class="item-show__comment-btn">
                        コメントを送信する
                    </button>
                </form>
            </section>

        </div>
    </div>
</div>
@endsection
