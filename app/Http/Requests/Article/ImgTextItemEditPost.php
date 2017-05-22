<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class ImgTextItemEditPost extends FormRequest
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
            'content' => 'bail|required',
            'image' => 'bail|required',
            'sort' => 'bail|required|numeric',
            'show' => 'bail|required|in:1,2',
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
            'content.required' => '内容不为空',
            'image.required' => '图片不为空',
            'sort.required' => '排序不为空',
            'sort.numeric' => '排序必须为数字',
            'show.required' => '请选择可见状态',
            'show.in' => '请选择可见状态',
        ];
    }
}
