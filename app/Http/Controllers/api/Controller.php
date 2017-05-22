<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\api\service\Error;

class Controller extends BaseController
{
    public function __construct(){
    }
    public function respSuccess($data){
        return response()->json([
            'status' => true,
            'code' => 200,
            'msg' => '成功',
            'data' => $data
        ]);
    }
    public function respError($code = 400,$data = []){
        return response()->json([
            'status' => false,
            'code' => $code,
            'msg' => Error::getError($code),
            'data' => $data
        ]);
    }
    /**
     * 格式化时间
     *
     * 返回[14:25] > [昨天14:28] > [05.18] > [2016.08]
     * 
     * @param (datetime || timestamp) $date 需要进行格式化的时间[时间戳,时间字符串]
     * @return (string || false) 参数[非时间戳]或[非时间字符串],返回false
     **/
    public function formatDate($date)
    {
        # 默认设置当前时间
        if(! isset($date)){
            return false;
        }
        # 判断$date是时间戳|将字符串转换为时间戳
        if(! is_int($date)) {
            if(! $date = strtotime($date)){
                return false;
            }
        }
        $gap = time() - $date;
        $day = 3600 * 24;
        $month = $day * 30;
        $year = $month * 12;
        if($gap < $day) {
            return date('H:i',$date);
        }else if(($gap < $day * 2) && ($gap > $day)) {
            return '昨天' . date('H:i',$date);
        }else if($gap < $year) {
            return date('m.d',$date);
        }else {
            return date('Y.m',$date);
        }
    }
}
