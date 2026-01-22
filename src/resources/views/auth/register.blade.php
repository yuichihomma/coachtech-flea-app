@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2 class="auth-title">会員登録</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- 全体エラーメッセージ --}}
        @if ($errors->any())
            <div class="auth-form__error-summary">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="auth-form__error">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ユーザー名 --}}
        <div class="auth-form__group">
            <label class="auth-form__label">ユーザー名</label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="auth-form__input"
            >
            @error('name')
                <p class="auth-form__error">{{ $message }}</p>
            @enderror
        </div>

        {{-- メールアドレス --}}
        <div class="auth-form__group">
            <label class="auth-form__label">メールアドレス</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="auth-form__input"
            >
            @error('email')
                <p class="auth-form__error">{{ $message }}</p>
            @enderror
        </div>

        {{-- パスワード --}}
        <div class="auth-form__group">
            <label class="auth-form__label">パスワード</label>
            <input
                type="password"
                name="password"
                class="auth-form__input"
            >
            @error('password')
                <p class="auth-form__error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 確認用パスワード --}}
        <div class="auth-form__group">
            <label class="auth-form__label">確認用パスワード</label>
            <input
                type="password"
                name="password_confirmation"
                class="auth-form__input"
            >

            @error('password_confirmation')
                <p class="auth-form__error">{{ $message }}</p>
            @enderror

            {{-- confirmed エラーのみ表示 --}}
            @error('password')
                @if (str_contains($message, '一致'))
                    <p class="auth-form__error">{{ $message }}</p>
                @endif
            @enderror
        </div>

        <button type="submit" class="auth-form__button">
            登録する
        </button>
    </form>

    <p class="auth-form__link">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </p>
</div>
@endsection
