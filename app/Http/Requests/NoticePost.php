<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoticePost extends FormRequest
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
            'group' => 'bail|sometimes|required',
            'user' => 'bail|sometimes|required',
            'title' => 'bail|required|between:2,50',
            'content' => 'bail|required|between:1,200',
            'type' => 'bail|in:0,1,2,3',
        ];
    }
    /**
    * 获取已定义验证规则的错误消息。
    *
    * @return array
    */
    public function messages(){
        return [
            'group.required' => '请选择通知范围',
            'user.required' => '请选择通知范围',
            'title.required' => '标题必须输入',
            'title.between'  => '标题长度介于 :min - :max 之间',
            'content.required' => '内容必须输入',
            'content.between'  => '内容长度介于 :min - :max 之间',
            'type.in'  => '请选择消息等级',
        ];
    }
}
