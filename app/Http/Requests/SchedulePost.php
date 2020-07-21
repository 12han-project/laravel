<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchedulePost extends FormRequest
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
                'title' => 'bail|required|min:1|max:25',
                'url' => 'bail|required|url|max:255',
                'week' => 'bail|required',
                'time_limit' => 'bail|required'
        ];
    }

    public function messages()
    {
        return [
                'title.required' => '授業名を入れてください',
                'title.min' => '授業名に一つ以上の文字を入れてください',
                'title.max' => '授業名に２５文字以内にしてください',
                'url.required' => 'URLアドレスを入れてください',
                'url.url' => '正しいURLアドレスを入れてください',
                'url.max' => 'URLアドレスを２５５文字以内にしてください',
                'week.required' => '曜日を選択してください',
                'time_limit.required' => '時限を選択してください',
        ];
    }
}
