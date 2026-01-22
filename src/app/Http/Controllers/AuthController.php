<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 会員登録画面
    public function showRegister()
    {
        return view('auth.register');
    }

    // 会員登録処理
    public function register(RegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_completed' => false, // 初回フラグ
        ]);

        // 自動ログインしない（要件どおり）
        return redirect('login');
    }

    // ログイン画面
    public function showLogin()
    {
        return view('auth.login');
    }

    // ログイン処理
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // ★ ここがFN006の核心
            $user = Auth::user();

            return redirect('/');
        }

        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません。',
        ]);
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
