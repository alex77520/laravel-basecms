<?php
namespace App\Http\Controllers\api\service;

class Regex {
    /**
     * 手机号验证
     *
     * @param type var Description
     **/
    public static function mobile($mobile)
    {
        if(preg_match('/^1[34578]{1}\d{9}$/',$mobile)){
            return $mobile;
        }
        return false;
    }
}