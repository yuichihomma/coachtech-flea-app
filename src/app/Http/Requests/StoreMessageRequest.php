<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ルート側で認可してるならtrueでOK
    }

    public function rules(): array
    {
        return [
            'body'   => ['required', 'string', 'max:400'],
            'image'  => ['nullable', 'file', 'mimes:jpeg,png', 'max:1024'], 
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => '本文を入力してください',
            'body.max'      => '本文は400文字以内で入力してください',
            'image.mimes'   => '「png」または「jpeg」形式でアップロードしてください',
            'image.max'     => '画像は1MB以下でアップロードしてください',
            'image.uploaded' => '画像のアップロードに失敗しました。1MB以下の画像を選択してください',
        ];
    }
}
