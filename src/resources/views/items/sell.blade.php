@extends('layouts.app')

@section('content')
<div class="sell-page">
  <div class="sell-box">

    <h1 class="sell-title">商品の出品</h1>

    <form action="/sell" method="POST" enctype="multipart/form-data">
      @csrf

      {{-- 全体エラーメッセージ --}}
      @if ($errors->any())
        <div class="sell-error-summary">
          <ul>
            @foreach ($errors->all() as $error)
              <li class="sell-error">{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- 商品画像 --}}
      <div class="sell-block">
        <label class="block-title">商品画像</label>

        <div class="image-area">
          <label class="image-btn">
            画像を選択する
            <input
              type="file"
              name="image"
              accept="image/*"
              class="image-input"
            >
          </label>
        </div>

        @error('image')
          <p class="sell-error">{{ $message }}</p>
        @enderror

        <p class="image-note">
          ※ 画像を選択後、「出品する」を押すとアップロードされます
        </p>
      </div>

      {{-- 商品の詳細 --}}
      <div class="sell-block">
        <h2 class="section-title">商品の詳細</h2>

        {{-- カテゴリー --}}
        <label class="block-title">カテゴリー</label>

        <div class="category-wrapper">
          @foreach ([
            1 => 'ファッション',
            2 => '家電',
            3 => 'インテリア',
            4 => 'レディース',
            5 => 'メンズ',
            6 => 'コスメ',
            7 => '本',
            8 => 'ゲーム',
            9 => 'スポーツ',
            10 => 'キッチン',
            11 => 'ハンドメイド',
            12 => 'アクセサリー',
            13 => 'おもちゃ',
            14 => 'ベビー・キッズ'
          ] as $id => $label)
            <label class="category-item">
              <input
                type="checkbox"
                name="category_ids[]"
                value="{{ $id }}"
                {{ in_array($id, old('category_ids', [])) ? 'checked' : '' }}
              >
              <span>{{ $label }}</span>
            </label>
          @endforeach
        </div>

        @error('categories')
          <p class="sell-error">{{ $message }}</p>
        @enderror

        {{-- 商品の状態 --}}
        <label class="block-title">商品の状態</label>
        <select name="condition" class="select-box">
          <option value="">選択してください</option>
          @foreach ([
            '新品・未使用',
            '未使用に近い',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '傷や汚れあり'
          ] as $condition)
            <option
              value="{{ $condition }}"
              {{ old('condition') === $condition ? 'selected' : '' }}
            >
              {{ $condition }}
            </option>
          @endforeach
        </select>

        @error('condition')
          <p class="sell-error">{{ $message }}</p>
        @enderror
      </div>

      {{-- 商品名と説明 --}}
      <div class="sell-block">
        <h2 class="section-title">商品名と説明</h2>

        {{-- 商品名 --}}
        <label class="block-title">商品名</label>
        <input
          type="text"
          name="name"
          class="input-box"
          value="{{ old('name') }}"
        >
        @error('name')
          <p class="sell-error">{{ $message }}</p>
        @enderror

        {{-- ブランド名（任意） --}}
        <label class="block-title">ブランド名</label>
        <input
          type="text"
          name="brand"
          class="input-box"
          value="{{ old('brand') }}"
        >

        {{-- 商品説明 --}}
        <label class="block-title">商品の説明</label>
        <textarea
          name="description"
          class="textarea-box"
        >{{ old('description') }}</textarea>
        @error('description')
          <p class="sell-error">{{ $message }}</p>
        @enderror

        {{-- 販売価格 --}}
        <label class="block-title">販売価格</label>
        <div class="price-box">
          <span>¥</span>
          <input
            type="text"
            name="price"
            class="price-input"
            inputmode="numeric"
            pattern="[0-9]*"
            value="{{ old('price') }}"
          >
        </div>
        @error('price')
          <p class="sell-error">{{ $message }}</p>
        @enderror
      </div>

      {{-- 出品ボタン --}}
      <button type="submit" class="submit-btn">
        出品する
      </button>

    </form>

  </div>
</div>
@endsection
