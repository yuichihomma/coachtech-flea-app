@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="tabs">
    <a
        href="{{ route('items.index', ['keyword' => request('keyword')]) }}"
        class="{{ request('tab') !== 'mylist' ? 'active' : '' }}">
        おすすめ
    </a>

    @auth
    <a
    href="{{ route('items.index', [
        'tab' => 'mylist',
        'keyword' => request('keyword')
    ]) }}"
    class="{{ request('tab') === 'mylist' ? 'active' : '' }}">
        マイリスト
    </a>
    @endauth
</div>
@php
    use Illuminate\Support\Str;
@endphp

<div class="item-list">
@forelse ($items as $item)
    <div class="item-card">
        <a href="{{ route('items.show', $item->id) }}">

            @if ($item->img_url)
                @if (Str::startsWith($item->img_url, ['http://', 'https://']))
                    {{-- seedデータ（S3などの外部URL） --}}
                    <img src="{{ $item->img_url }}" alt="商品画像">
                @else
                    {{-- 自分で出品した画像（storage） --}}
                    <img src="{{ asset('storage/' . $item->img_url) }}" alt="商品画像">
                @endif
            @else
                <div class="item-image--placeholder">商品画像</div>
            @endif

            @if ($item->is_sold)
                <span class="sold">Sold</span>
            @endif

            <p class="item-name">{{ $item->name }}</p>
        </a>
    </div>
@empty
@endforelse
</div>
@endsection
