<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class ClassifyAddPost extends FormRequest
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
            'name' => 'bail|required|between:2,20',
            // 'key' => 'bail|required|between:1,20',
            'show' => 'bail|in:1,2',
        ];
    }
    /**
    * 获取已定义验证规则的错误消息。
    *
    * @return array
    */
    public function messages()
    {
        return [
            'name.required' => '名称必须填写',
            'name.between' => '名称长度:min - :max位',
            // 'key.required'  => 'API键必须填写',
            // 'key.between' => 'API键长度:min - :max位',
            'show.in' => '请勾选状态'
        ];
    }
}
