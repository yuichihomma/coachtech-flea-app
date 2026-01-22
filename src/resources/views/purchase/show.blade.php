@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')

<div class="purchase-page">
  <div class="purchase-container">

    {{-- 左カラム --}}
    <div class="purchase-left">

      {{-- 商品情報 --}}
      <div class="purchase-item">
        @php use Illuminate\Support\Str; @endphp

        <div class="purchase-item-image">
          @if ($item->img_url)
            @if (Str::startsWith($item->img_url, ['http://', 'https://']))
              <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
            @else
              <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}">
            @endif
          @else
            <div class="item-image-placeholder">商品画像</div>
          @endif
        </div>

        <div class="purchase-item-text">
          <p class="purchase-item-name">{{ $item->name }}</p>
          <p class="purchase-item-price">¥{{ number_format($item->price) }}</p>
        </div>
      </div>

      <hr>

      {{-- 支払い方法 --}}
      <div class="purchase-section">
        <h3 class="section-title">支払い方法</h3>

        <select
          name="payment_method"
          id="paymentMethodSelect"
          class="select-box"
          form="purchase-form"
        >
          <option value="">選択してください</option>
          <option value="convenience" {{ old('payment_method') === 'convenience' ? 'selected' : '' }}>
            コンビニ払い
          </option>
          <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>
            クレジットカード
          </option>
        </select>

        @error('payment_method')
          <p class="purchase-error">{{ $message }}</p>
        @enderror
      </div>

      <hr>

      {{-- 配送先 --}}
      <div class="purchase-section">
        <div class="address-header">
          <h3 class="section-title">配送先</h3>
          <a href="{{ route('purchase.address', $item->id) }}">変更する</a>
        </div>

        @if($user && $user->postcode)
          <p>〒 {{ $user->postcode }}</p>
          <p>{{ $user->address }}</p>
          <p>{{ $user->building }}</p>
        @else
          <p>住所が登録されていません</p>
        @endif

        @error('address_id')
          <p class="purchase-error">{{ $message }}</p>
        @enderror
      </div>
    </div>

    {{-- 右カラム --}}
    <div class="purchase-right">
      <div class="purchase-summary">
        <div class="summary-row">
          <span>商品代金</span>
          <span>¥{{ number_format($item->price) }}</span>
        </div>
        <div class="summary-row">
          <span>支払い方法</span>
          <span id="paymentMethodDisplay">
            {{ old('payment_method') === 'card'
                ? 'クレジットカード'
                : (old('payment_method') === 'convenience'
                    ? 'コンビニ払い'
                    : '未選択') }}
          </span>
        </div>
      </div>

      <form id="purchase-form"
            action="{{ route('purchase.store', $item->id) }}"
            method="POST">
        @csrf

        {{-- 配送先ID（必須） --}}
        <input
          type="hidden"
          name="address_id"
          value="{{ $user->address_id ?? '' }}"
        >

        {{-- 全体エラーメッセージ --}}
        @if ($errors->any())
          <div class="purchase-error-summary">
            <ul>
              @foreach ($errors->all() as $error)
                <li class="purchase-error">{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <button class="purchase-btn">
          購入する
        </button>
      </form>
    </div>

  </div>
</div>

{{-- 支払い方法 表示切り替え --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('paymentMethodSelect');
    const display = document.getElementById('paymentMethodDisplay');

    if (!select || !display) return;

    const labels = {
        card: 'クレジットカード',
        convenience: 'コンビニ払い'
    };

    select.addEventListener('change', function () {
        display.textContent = labels[this.value] ?? '未選択';
    });
});
</script>
@endsection
