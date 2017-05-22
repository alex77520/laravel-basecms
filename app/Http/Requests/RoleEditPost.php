<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleEditPost extends FormRequest
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
            'name' => 'bail|required|between:2,10',
            'intro' => 'bail|between:1,50',
        ];
    }
    /**
    * 获取已定义验证规则的错误消息。
    *
    * @return array
    */
    public function messages(){
        return [
            'name.required' => '角色名必须输入',
            'name.between'  => '角色名长度介于 :min - :max 之间',
            'intro.between'  => '简介长度介于 :min - :max 之间',
        ];
    }
}
