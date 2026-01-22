<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| 公開（未ログインOK）
|--------------------------------------------------------------------------
*/
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

Route::post('/item/{item}/comment', [CommentController::class, 'store'])
    ->name('comments.store');

// ==============================
// 認証（登録・ログイン）
// ==============================

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ログイン必須（メール未認証OK）
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // 出品
    Route::get('/sell', fn () => view('items.sell'))->name('sell');
    Route::post('/sell', [ItemController::class, 'store']);

});

/*
|--------------------------------------------------------------------------
| ログイン + メール認証必須（FN012の本丸）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // 購入
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])
        ->name('purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])
        ->name('purchase.store');

        // ✅ 決済成功画面
    Route::get('/purchase/{item_id}/success', [PurchaseController::class, 'success'])
        ->name('purchase.success');

    // 住所
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'address'])
        ->name('purchase.address');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])
        ->name('purchase.address.update');

    // マイページ
    Route::get('/mypage', [MypageController::class, 'show'])
        ->name('mypage.show');
    Route::get('/mypage/profile', [MypageController::class, 'edit'])
        ->name('mypage.edit');
    Route::post('/mypage/profile', [MypageController::class, 'update'])
        ->name('mypage.update');

    // いいね
    Route::post('/item/{item}/like', [LikeController::class, 'store'])
        ->name('item.like');

    
});

/*
|--------------------------------------------------------------------------
| メール認証誘導画面（FN013）
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
});
