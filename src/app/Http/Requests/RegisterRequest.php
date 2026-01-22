<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * 認可
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:20',
            ],
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
            'password_confirmation' => [
                'required',
                'min:8',
            ],
        ];
    }

    /**
     * エラーメッセージ
     */
    public function messages(): array
    {
        return [
            'name.required' => 'お名前を入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => 'メールアドレスはメール形式で入力してください。',

            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.confirmed' => 'パスワードと一致しません。',

            'password_confirmation.required' => '確認用パスワードを入力してください。',
            'password_confirmation.min' => '確認用パスワードは8文字以上で入力してください。',
        ];
    }
}
