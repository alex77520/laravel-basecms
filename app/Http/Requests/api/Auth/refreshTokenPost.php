<?php

namespace App\Http\Requests\api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class refreshTokenPost extends FormRequest
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
            'refresh_token' => 'bail|required|between:38,38',
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
            'refresh_token.required' => '刷新令牌缺失',
            'refresh_token.between' => '刷新令牌长度不正确',
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
