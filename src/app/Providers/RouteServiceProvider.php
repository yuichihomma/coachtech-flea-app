<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * ログイン・メール認証完了後のリダイレクト先
     *
     * FN012-4:
     *  - 初回会員登録
     *  - メール認証誘導画面
     *  - メール認証
     *  - プロフィール設定画面
     */
    public const HOME = '/mypage/profile';

    /**
     * アプリケーションのルート定義を読み込む
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }

    /**
     * レートリミット設定（Fortify / 認証系で使用）
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        });
    }
}
