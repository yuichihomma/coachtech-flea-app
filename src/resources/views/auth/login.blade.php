@extends('layouts.app')

@section('content')
<div class="login">
    <div class="login__container">
        <h2 class="login__title">ログイン</h2>

        <form method="POST" action="{{ route('login') }}" class="login__form">
            @csrf

            {{-- 全体エラーメッセージ --}}
            @if ($errors->any())
                <div class="login__error-summary">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="login__error">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="login__group">
                <label class="login__label">メールアドレス</label>
                <input
                    type="email"
                    name="email"
                    class="login__input"
                    value="{{ old('email') }}"
                >
                @error('email')
                    <p class="login__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="login__group">
                <label class="login__label">パスワード</label>
                <input
                    type="password"
                    name="password"
                    class="login__input"
                >
                @error('password')
                    <p class="login__error">{{ $message }}</p>
                @enderror
            </div>

            {{-- 認証失敗メッセージ用（Auth::attempt失敗など） --}}
            @error('login')
                <p class="login__error">{{ $message }}</p>
            @enderror

            <button type="submit" class="login__button">
                ログインする
            </button>
        </form>

        <a href="{{ route('register') }}" class="login__register-link">
            会員登録はこちら
        </a>
    </div>
</div>
@endsection
