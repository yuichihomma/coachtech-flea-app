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
            'image'  => ['nullable', 'file', 'extensions:jpeg,png', 'mimetypes:image/jpeg,image/png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => '本文を入力してください',
            'body.max'      => '本文は400文字以内で入力してください',
            'image.extensions' => '「png」または「jpeg」形式でアップロードしてください',
            'image.mimetypes'  => '「png」または「jpeg」形式でアップロードしてください',
            'image.max'        => '画像は2MB以下でアップロードしてください',
            'image.uploaded'   => '画像のアップロードに失敗しました。2MB以下の画像を選択してください',
        ];
    }
}
