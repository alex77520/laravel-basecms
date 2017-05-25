<?php

namespace App\Http\Requests\api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class registPost extends FormRequest
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
            'mobile' => 'bail|required|regex:/^1[34578]{1}\d{9}$/',
            'password' => 'bail|required|between:32,32',
            'code' => 'bail|required',
            'sms_key' => 'bail|required'
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
            'mobile.required' => '手机号码缺失',
            'mobile.regex' => '手机号码格式不正确',
            'password.required' => '密码缺失',
            'password.between' => '密码长度不正确',
            'code.required' => '验证码缺失',
            'sms_key.required' => '短信校验码缺失'
        ];
    }
    protected function formatErrors(Validator $validator)
    {
        return [
            0 =>$validator->errors()->first()
        ];
    }
    public function response(array $errors)
    {
        return response()->json([
            'status' => false,
            'code' => 403,
            'msg' => '拒绝提供资源',
            'data' => $errors[0]
        ]);
    }
}
