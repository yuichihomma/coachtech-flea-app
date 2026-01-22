@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage-edit.css') }}">
@endsection

@section('content')
<div class="profile-edit-container">

    <h2 class="edit-title">プロフィール設定</h2>

    <form action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 全体エラーメッセージ --}}
        @if ($errors->any())
            <div class="profile-error-summary">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="profile-error">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- プロフィール画像 -->
        <div class="image-area">
            <div class="profile-image">
                <img
                    id="profilePreview"
                    src="{{ auth()->user()->image
                        ? asset('storage/' . auth()->user()->image)
                        : asset('images/default-profile.png') }}"
                    alt="">
            </div>

            <label class="image-btn">
                画像を選択する
                <input
                    type="file"
                    name="image"
                    id="profileImageInput"
                    hidden
                    accept="image/*">
            </label>

            @error('image')
                <p class="profile-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- ユーザー名 -->
        <div class="form-group">
            <label>ユーザー名</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', auth()->user()->name) }}">
            @error('name')
                <p class="profile-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 郵便番号 -->
        <div class="form-group">
            <label>郵便番号</label>
            <input
                type="text"
                name="postcode"
                placeholder="123-4567"
                value="{{ old('postcode', auth()->user()->postcode) }}">
            @error('postcode')
                <p class="profile-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 住所 -->
        <div class="form-group">
            <label>住所</label>
            <input
                type="text"
                name="address"
                value="{{ old('address', auth()->user()->address) }}">
            @error('address')
                <p class="profile-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- 建物名（任意） -->
        <div class="form-group">
            <label>建物名</label>
            <input
                type="text"
                name="building"
                value="{{ old('building', auth()->user()->building) }}">
        </div>

        <button type="submit" class="update-btn">
            更新する
        </button>
    </form>

</div>

<!-- 🔽 画像即時反映用JS -->
<script>
document.getElementById('profileImageInput').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    // 画像以外を防ぐ
    if (!file.type.startsWith('image/')) {
        alert('画像ファイルを選択してください');
        e.target.value = '';
        return;
    }

    // サイズ制限（2MB）
    if (file.size > 2 * 1024 * 1024) {
        alert('画像サイズは2MB以下にしてください');
        e.target.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function (event) {
        document.getElementById('profilePreview').src = event.target.result;
    };
    reader.readAsDataURL(file);
});
</script>
@endsection
