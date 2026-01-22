@extends('layouts.app')

@section('content')
<div class="verify-page">
    <div class="verify-container">

        <p class="verify-message">
            登録していただいたメールアドレスに認証メールを送信しました。<br>
            メール認証を完了してください。
        </p>

        {{-- 認証はこちらから（MailHogへ） --}}
        <a href="http://localhost:8025" target="_blank" class="verify-btn">
            認証はこちらから
        </a>

        {{-- 再送 --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="verify-resend">
                認証メールを再送する
            </button>
        </form>

        @if (session('status') === 'verification-link-sent')
            <p class="verify-success">
                認証メールを再送しました。
            </p>
        @endif

    </div>
</div>
@endsection
