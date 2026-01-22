<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment_method' => [
                'required',
            ],
            'address_id' => [
                'required',
            ],
        ];
    }

    /**
     * エラーメッセージ
     */
    public function messages(): array
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'address_id.required' => '配送先を選択してください。',
        ];
    }
}
