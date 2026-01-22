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

            <p class="profile-name">{{ $user->name }}</p>
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
    </div>

    {{-- 出品した商品 --}}
    @if ($tab === 'sell')
        <div class="product-grid">
            @forelse ($listedItems as $item)
                <div class="product-card">
                    <img src="{{ asset('storage/' . $item->img_url) }}" class="product-image" alt="{{ $item->name }}">

                    @if ($item->is_sold)
                        <span class="sold-label">Sold</span>
                    @endif

                    <p class="product-name">{{ $item->name }}</p>
                </div>
            @empty
                <p>出品した商品はありません</p>
            @endforelse
        </div>
    @endif

    {{-- 購入した商品 --}}
    @if ($tab === 'buy')
        <div class="product-grid">
            @forelse ($purchasedItems as $item)
                <div class="product-card">
                    <img src="{{ $item->img_url }}" class="product-image">
                    <p class="product-name">{{ $item->name }}</p>
                </div>
            @empty
                <p>購入した商品はありません</p>
            @endforelse
        </div>
    @endif

</div>
@endsection
