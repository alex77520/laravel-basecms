<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

use App\Http\Requests\UserRegistPost;
use App\Http\Requests\UserLoginPost;
use App\Models\Users;
use Cookie;
use Hash;
use App\Events\UserRegist;


class AuthController extends Controller
{
    /**
     * 账号登录Action
     *
     * @param type var Description
     **/
    public function login(UserLoginPost $request){
        // 执行登录
        $username = $request->input('username');
        $password = $request->input('password');
        
        $User = Users::where('account',$username)
                        ->where('power',true)
                        ->first();
        if(!$User){
            return parent::ajaxError('该账号已注销或不存在');
        }
        if(!Hash::check($password, $User->password)){
            return parent::ajaxError('用户名或密码错误');
        }
        # 存储缓存服务器
        $rs = parent::setWebUserInfo($User,$token);
        if(!$rs){
            return parent::ajaxError('服务器异常，请联系管理员');
        }
        # 设置SESSION
        $request->session()->put('ticket', $token);
        return parent::ajaxSuccess($token);
    }
    /**
     * 注册账号Action
     *
     * @param type var Description
     **/
    public function regist(UserRegistPost $request)
    {
        $username = $request->input('username');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $User = new Users;
        $User->account = $username;
        $User->email = $email;
        $User->nickname = "新用户".$username;
        $User->password = $password;
        $User->last_login = date('Y-m-d H:i:s',time());
        $registResult = $User->save();
        if($registResult){
            event(new UserRegist($User));
            return parent::ajaxSuccess('注册成功');
        }else{
            return parent::ajaxError('服务器异常，请稍后重试');
        }
    }

    /**
     * 退出登录Action
     *
     * @param type var Description
     **/
    public function logout(Request $request){
        $request->session()->forget('ticket');
        Cookie::queue('app.vcomer.cn_autologin_key',null,-1);
        return redirect('/');
    }
}
