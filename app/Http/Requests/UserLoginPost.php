<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginPost extends FormRequest
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
            'username' => 'bail|required|between:5,20|alpha_dash|',
            'password' => 'bail|required|between:8,20'
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
            'username.required' => '用户名必须填写',
            'username.alpha_dash' => '用户名只能由字母、数字，下划线组成',
            'username.between' => '用户名长度:min - :max位',
            'password.required' => '密码必须填写',
            'password.between' => '密码长度:min - :max位'
        ];
    }
}
