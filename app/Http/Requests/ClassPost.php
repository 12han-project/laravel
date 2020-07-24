<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'name' => 'bail|required|min:1|max:25',
//            'student' => 'bail|min:5',
        ];
    }

    public function messages()
    {
        return [
//                'name.required' => '授業名に一つ以上の文字を入れてください.',
//                'name.min' => '授業名に一つ以上の文字を入れてください.',
//                'name.max' => '授業名に25文字以内にしてください.',
//                'student.required' => '学生番号を入力してください．',
        ];
    }
}
