@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h2 class="address-title">住所の変更</h2>

    <form action="{{ route('purchase.address.update', $item_id) }}" method="POST">
        @csrf

        {{-- 全体エラーメッセージ --}}
        @if ($errors->any())
            <div class="address-error-summary">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="address-error">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label for="postcode">郵便番号</label>
            <input
                type="text"
                id="postcode"
                name="postcode"
                value="{{ old('postcode') }}"
                placeholder="123-4567"
            >
            @error('postcode')
                <p class="address-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input
                type="text"
                id="address"
                name="address"
                value="{{ old('address') }}"
            >
            @error('address')
                <p class="address-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input
                type="text"
                id="building"
                name="building"
                value="{{ old('building') }}"
            >
        </div>

        <button type="submit" class="update-btn">
            更新する
        </button>
    </>
</div>
@endsection
