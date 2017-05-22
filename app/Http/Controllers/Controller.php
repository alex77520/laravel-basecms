<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function ajaxSuccess($data){
        return response()->json([
            'code' => 1,
            'data' => $data
        ]);
    }

    public function ajaxError($data,$code = 500){
        return response()->json([
            'code'  => 0,
            'state' => isset($code) ? $code : 500,
            'data'   => isset($data) ? $data : '服务器异常，请稍后重试',
        ]);
    }
    
    public function ResourcePath($path = null,$name = null){
        if($path === null || $name === null ) {
            return asset('web/v1/assets/images/default.jpg');
        }
        # 返回显示的图片路径
        return asset('storage/uploads/'.$path."/".$name);
    }

    public function getSize($byte,$isMB = true){
        if($isMB){
            return round($byte / 1024 / 1024,2) . "M";
        }else{
            return round($byte / 1024 ,2) . "K";
        }
    }
}
