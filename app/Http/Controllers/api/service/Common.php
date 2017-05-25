<?php
namespace App\Http\Controllers\api\service;

class Common {
    /**
     * 执行爬虫
     *
     * $uri[访问地址],$isPost[是否为Post],$data[传输的数据,数组格式];
     * $cookie_file[存储Cookie地址],$set_cookie[是否存储Cookie]
     *
     * @param type var Description
     **/
    public static function doCurl($uri,$isPost = false,$data = NULL,$cookie_file = null,$set_cookie = false)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        if($isPost){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        } else {
            $symbol = strstr($uri,"?") ? "&" : "?";
            $uri = $data == NULL ? $uri : $uri . $symbol . http_build_query($data);
        }
        if($cookie_file != null){
            if($set_cookie === true){
                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file); # 存储Cookie
            }else{
                curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); # 携带Cookie
            }
        }
        $urlPrefix = substr($uri,0,5);
        if($urlPrefix == "https"){
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        }
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer 
        curl_setopt($ch,CURLOPT_URL,$uri);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    /**
     * 聚合接口，发送短信
     *
     * 仅执行发送短信的操作，不做任何逻辑判断，请在调用处做逻辑判断后再触发该方法
     *
     * @param type var Description
     **/
    public static function JuheSendSMS($mobile,$code)
    {
        $appid = config('cms.juhe_appid');
        if($appid == NULL){
            return false;
        }
        $uri = 'http://v.juhe.cn/sms/send';
        $pushData = [
            'mobile' => $mobile,
            'tpl_id' => '19991',
            'tpl_value' => urlencode('#code#='.$code),
            'key' => $appid
        ];
        try{
            $result = json_decode(self::doCurl($uri,false,$pushData),true);
        // dd($pushData);
        }catch(\Exception $e){
            return false;
        }
        if($result){
            if($result['error_code'] == 0){
                return true;
            }
        }
        return false;
    }

    /**
     * 生成随机数字
     *
     * @param type var Description
     **/
    public static function random_num($length = 6)
    {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }
}