@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="profile-container">

    {{-- プロフィール --}}
    <div class="profile-header">
        <div class="profile-left">
            <div class="mypage-icon">
                @if(Auth::user()->image)
                <img src="{{ asset('storage/' . Auth::user()->image) }}" class="mypage-icon__img">
                    @else
                        <img src="{{ asset('images/default-user.png') }}" class="mypage-icon__img">
                    @endif
            </div>
            <div class="profile-meta">
                <p class="profile-name">{{ $user->name }}</p>
                @if(!empty($ratingCount) && $ratingCount > 0)
                    <div class="mypage-rating" aria-label="評価 {{ $ratingAvgRounded }} / 5">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $ratingAvgRounded ? 'is-on' : '' }}">★</span>
                        @endfor
                    </div>
                @endif
            </div>
        </div>

        <a href="{{ route('mypage.edit') }}" class="profile-edit-btn">
            プロフィールを編集
        </a>
    </div>

    {{-- タブ --}}
    <div class="profile-tabs">
        <a href="{{ route('mypage.show') }}?tab=sell"
           class="tab {{ $tab === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>

        <a href="{{ route('mypage.show') }}?tab=buy"
           class="tab {{ $tab === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>

        <a href="{{ route('mypage.show') }}?tab=trading"
   class="tab {{ $tab === 'trading' ? 'active' : '' }}">
    取引中の商品
    @if (!empty($tradingUnreadTotal) && $tradingUnreadTotal > 0)
        <span class="tab-badge">
            {{ $tradingUnreadTotal > 99 ? '99+' : $tradingUnreadTotal }}
        </span>
    @endif
</a>
    </div>

   {{-- 出品した商品 --}}
@if ($tab === 'sell')
    <div class="product-grid">
        @forelse ($listedItems as $item)
            <div class="product-card">
                <div class="product-image-wrap">
                    <img src="{{ $item->img_url }}" class="product-image" alt="{{ $item->name }}">
                    @if ($item->is_sold)
                        <span class="sold-label">Sold</span>
                    @endif
                </div>
                <p class="product-name">{{ $item->name }}</p>
            </div>
        @empty
            <p>出品した商品はありません</p>
        @endforelse
    </div>

{{-- 購入した商品 --}}
@elseif ($tab === 'buy')
    <div class="product-grid">
        @forelse ($purchasedItems as $item)
            <div class="product-card">
                <img src="{{ $item->img_url }}" class="product-image" alt="{{ $item->name }}">
                <p class="product-name">{{ $item->name }}</p>
            </div>
        @empty
            <p>購入した商品はありません</p>
        @endforelse
    </div>

{{-- 取引中の商品 --}}
@elseif ($tab === 'trading')
    <div class="product-grid">
        @forelse ($tradingChatRooms as $room)
            <div class="product-card product-card--notify">
                <a href="{{ route('chat.show', $room) }}" class="product-link-wrap">

                    @php $img = $room->item->img_url; @endphp

                    @if ($img && (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')))
                        <img src="{{ $img }}" class="product-image" alt="{{ $room->item->name }}">
                    @else
                        <img src="{{ asset('storage/' . $img) }}" class="product-image" alt="{{ $room->item->name }}">
                    @endif

                    {{-- 未読バッジ --}}
                    @if ($room->unread_count > 0)
                        <span class="notify-badge">
                            {{ $room->unread_count > 99 ? '99+' : $room->unread_count }}
                        </span>
                    @endif
                </a>

                <p class="product-name">{{ $room->item->name }}</p>
            </div>
        @empty
            <p>取引中の商品はありません</p>
        @endforelse
    </div>
@endif
</div>
@endsection
