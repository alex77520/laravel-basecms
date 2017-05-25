<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requests;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\api\service\Error;
use Illuminate\Support\Facades\Redis;

class Controller extends BaseController
{
    public static $data = [
        'user'  => null,
        'appid' => null,
        'token' => null
    ];

    public function __construct(){
        self::$data['appid'] = Requests::header('appid');
        if(Requests::header('token')){
            self::$data['token'] = Requests::header('token');
            self::$data['user'] = self::getMemberInfo();
        }
    }
    /**
     * 设置用户信息
     *
     * @param type var Description
     **/
    public function setMemberInfo($user,&$token)
    {
        $userModel = new \StdClass();
        $userModel->mid = $user->mid;
        $userModel->mobile = $user->mobile;
        $userModel->nickname = $user->nickname;
        $userModel->avatar = $user->avatar;
        $token = str_random(28);
        $redisKey = self::$data['appid'] . ":userinfo:" . $token;
        $redisValue = json_encode($userModel);
        # 存入redis数据库中
        $rs = Redis::set($redisKey , $redisValue);
        if($rs){
            # 设置一天的过期时间
            Redis::expire($redisKey , 24 * 3600);
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取用户信息
     *
     * @param type var Description
     **/
    public function getMemberInfo()
    {
        $key = self::$data['appid'] .":userinfo:" . self::$data['token'];
        $userInfo = Redis::get($key);
        if(!$userInfo){
            return null;
        }else{
            return json_decode($userInfo);
        }
    }
    /**
     * 删除用户信息
     *
     * @param type var Description
     **/
    public function removeMemberInfo($token = null)
    {
        if($token == null){
            $token = self::$data['token'];
        }
        $key = self::$data['appid'] .":userinfo:" . $token;
        if(Redis::del($key))
        {
            return true;
        }
        return false;
    }
    public static function respSuccess($data){
        return response()->json([
            'status' => true,
            'code' => 200,
            'msg' => '成功',
            'data' => $data
        ]);
    }
    public static function respError($code = 400,$data = []){
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
    /**
     * 验证短信验证码
     *
     * @param type var Description
     **/
    public function verifySMS($mobile,$code,$key)
    {
        if(!$redisData = Redis::get($key)){
            return false;
        }
        $redisData = json_decode($redisData,true);
        if( ( $mobile === $redisData['mobile'] ) && ( $code === (string)$redisData['code'] ) ){
            Redis::del($key);
            return true;
        }
        return false;
    }
}
