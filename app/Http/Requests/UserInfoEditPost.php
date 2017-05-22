<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserInfoEditPost extends FormRequest
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
            'nickname' => 'bail|required|between:2,20',
            'email' => 'bail|required|email|between:5,100',
            // 'avatar' => 'bail|required',
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
            'nickname.required' => '昵称必须填写',
            'nickname.between' => '用户名长度:min - :max位',
            'email.required'  => '邮箱必须填写',
            'email.email' => '邮箱格式不正确',
            'email.bettwen' => '邮箱格式不正确',
            // 'avatar.required' => '头像必须选择'
        ];
    }
}
