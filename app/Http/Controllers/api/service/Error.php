<?php

namespace App\Http\Controllers\api\service;

class Error
{
    /**
     * 错误码对应错误信息
     *
     * @var string
     **/
    public static $errCodes = [
        '200' => '成功',
        '400' => '未知错误',
        '401' => '未被授权的操作',
        '403' => '拒绝提供资源', # 有关于权限
        '404' => '资源不存在',
        '500' => '服务器异常',


        '1001' => '[app_id]缺失',
        '1002' => '[app_id]不存在或无权限',
        '1003' => '[sign]缺失',
        '1004' => '[sign]签名错误',
        '1005' => '[nonce]缺失',
        '1006' => '[nonce]格式错误',
        
        '10001' => '资源已存在'
    ];
    /**
     * 返回错误详细信息
     *
     * @param type $code 错误码，默认400[未知错误]
     * @param type $_    是否展示错误码，默认不展示
     **/
    public static function getError($code = '400',$_ = false){
        if(! isset(self::$errCodes[$code])) {
            $code = '400';
        }
        return ($_ ? '[{$code}]' : '')
                . self::$errCodes[$code];
    }
}
