<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class ImgTextEditPost extends FormRequest
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
            'classify' => 'bail|required',
            'title' => 'bail|required|between:2,50',
            'source' => 'bail|required|between:2,20',
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
            'classify.required' => '必须选择一个分类发布',
            'title.required' => '标题不为空',
            'title.between' => '标题长度:min - :max位',
            'source.required' => '信息来源不为空',
            'source.between' => '信息来源长度:min - :max位',
            'show.in' => '请选择可见状态',
        ];
    }
}
