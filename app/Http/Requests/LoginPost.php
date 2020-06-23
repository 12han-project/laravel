<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'username' => 'bail|required|alpha_num',
                'password' => 'bail|required',
        ];
    }

    public function messages()
    {
        return [
                'username.required' => 'ユーザー名の間違い',
                'username.alpha_num' => 'パスワードの間違い',
                'password.required' => 'パスワードを入力してください',
        ];
    }
}
