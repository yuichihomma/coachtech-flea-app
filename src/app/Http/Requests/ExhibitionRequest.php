<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'image' => [
                'required',
                'image',
            ],
            'name' => [
                'required',
                'max:20',
            ],
            'description' => [
                'required',
                'max:255',
            ],
            'category_ids'    => [
                'required',
                'array',
                'min:1',
            ],
            'category_ids.*'  => [
                'integer',
            ],
            'condition' => [
                'required',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    /**
     * エラーメッセージ
     */
    public function messages(): array
    {
        return [
            'image.required' => '商品画像を選択してください。',
            'image.image' => '商品画像は画像ファイルを選択してください。',

            'name.required' => '商品名を入力してください。',
            'name.max' => '商品名は20文字以内で入力してください。',

            'description.required' => '商品説明を入力してください。',
            'description.max' => '商品説明は255文字以内で入力してください。',

            'category_id.required' => 'カテゴリーを選択してください。',
            'condition.required' => '商品の状態を選択してください。',

            'price.required' => '商品価格を入力してください。',
            'price.numeric' => '商品価格は数値で入力してください。',
            'price.min' => '商品価格は0円以上で入力してください。',
        ];
    }
}
