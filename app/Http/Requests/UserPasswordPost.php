<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPasswordPost extends FormRequest
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
            'password' => 'bail|required|between:8,20',
            'repassword' => 'bail|required|same:password',
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
            'password.required' => '新密码必须填写',
            'password.between' => '新密码长度:min - :max位',
            'repassword.required'  => '请重复一遍新密码',
            'repassword.same' => '两次密码输入不一致',
        ];
    }
}
