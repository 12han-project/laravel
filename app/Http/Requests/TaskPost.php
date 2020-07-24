<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskPost extends FormRequest
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


        ];
    }

    public function messages()
    {
        return [
                'task_name.required' => '課題名を入れてください.',
                'task_name.min' => '課題名に一つ以上の文字を入れてください.',
                'task_name.max' => '課題名を25文字以内にしてください.',
                'upload.required' => 'ファイルを選択してください.',
        ];
    }
}
