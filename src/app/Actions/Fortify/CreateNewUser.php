<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Create a newly registered user.
     */
    public function create(array $input): User
    {
        // ★ FormRequest を強制的に実行（日本語バリデーション）
        app(RegisterRequest::class)->validateResolved();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'profile_completed' => false, // 初回フラグ（あれば）
        ]);
    }
}
