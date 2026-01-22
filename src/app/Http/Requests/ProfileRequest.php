<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['nullable', 'image'],
            'name' => ['required', 'max:20'],
            'postcode' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は20文字以内で入力してください',

            'postcode.required' => '郵便番号を入力してください',
            'postcode.regex' => '郵便番号は123-4567の形式で入力してください',

            'tel.required' => '電話番号を入力してください',
            'tel.max' => '電話番号は20文字以内で入力してください',

            'address.required' => '住所を入力してください',
        ];
    }
}
