<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">


        <title>@yield('title', 'COACHTECH フリマ')</title>
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a href="{{ url('/') }}" class="header__logo">
                <img src="{{ asset('images/coachtech-logo.png') }}" alt="COACHTECH">
            </a>

            <form action="{{ route('items.index') }}" method="GET" class="header__search">
    <input
        type="text"
        name="keyword"
        value="{{ request('keyword') }}"
        placeholder="何かお探しですか？"
    >

    @if (request('tab'))
        <input type="hidden" name="tab" value="{{ request('tab') }}">
    @endif
    <button type="submit" class="search-btn">検索</button>
</form>


            <nav class="header__nav">
            @guest
                <a href="/login">ログイン</a>
                <a href="/register">会員登録</a>
                <a href="/sell" class="btn-sell">出品</a>
            @endguest

            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="link-btn">ログアウト</button>
                </form>
                <a href="{{ route('mypage.show') }}">マイページ</a>
                <a href="/sell" class="btn-sell">出品</a>
            @endauth
            </nav>
        </div>
    </header>

    @yield('tabs')

    <main>
    @yield('content')
    </main>

</body>
</html>
