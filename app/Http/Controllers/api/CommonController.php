<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\Controller;
use App\Http\Controllers\api\service\Common;
use App\Http\Controllers\api\service\Regex;
use App\Models\Members;

use Illuminate\Support\Facades\Redis;
use Uuid;


class CommonController extends Controller
{
    /**
     * 发送短信验证码
     *
     * @param type var Description
     **/
    public function registSMS(Request $request)
    {
        if(!$request->has('mobile')){
            return parent::respError('400','[mobile]参数缺失');
        }
        if(!$mobile = Regex::mobile($request->input('mobile'))){
            return parent::respError('400','手机号格式不正确');
        }
        # 验证手机号是否已存在
        $isExist = Members::where('mobile',$mobile)->first();
        if($isExist != null){
            return parent::respError('403','手机号已被占用');
        }
        # 生成验证码
        $code = Common::random_num(6);
        # 生成随机ID，存入Redis
        $redisKey = (string)Uuid::generate();
        $redisValue = json_encode([
            'mobile' => $mobile,
            'code'   => $code
        ]);
        if(!Redis::set($redisKey, $redisValue)){
            return parent::respError('500');
        }
        Redis::expire($redisKey , 600); # 10分钟缓存
        # 发送验证码
        if(Common::JuheSendSMS($mobile,$code) === true){
            return parent::respSuccess($redisKey);
        }else{
            # 清除缓存
            Redis::del($redisKey); 
            return parent::respError('500','短信发送失败！');
        }
    }
    /**
     * 校验短信验证码
     *
     * @param type var Description
     **/
    public function checkSMS(Request $request)
    {
        if(!$request->has('mobile')){
            return parent::respError('400','手机号缺失');
        }
        if(!$request->has('code')){
            return parent::respError('400','验证码缺失');
        }
        if(!$request->has('sms_key')){
            return parent::respError('400','验证参数缺失');
        }
        if(!$mobile = Regex::mobile($request->input('mobile'))){
            return parent::respError('400','手机号格式不正确');
        }
        $key = $request->input('sms_key');
        $code = $request->input('code');

         # 获取Redis中的记录
        if(parent::verifySMS($mobile,$code,$key) === true){
            return parent::respSuccess('验证成功');
        }
        return parent::respError('400','验证码不正确');
    }
}
