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
        $remember = $request->input('remember');
        
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
        # 如果勾选了保持7天登录
        if($remember === 'remember'){
            parent::setCookieUserInfo($User,$cookieToken); # 设置自动登录7天
            Cookie::queue('cms_autologin_key',$cookieToken,7*3600*24);
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
        Cookie::queue('cms_autologin_key',null,-1);
        return redirect('/');
    }
    /**
     * 自动登录
     *
     * @param type var Description
     **/
    public function autoLogin(Request $request){
        $cookie = $request->cookie('cms_autologin_key');
        $userInfo = false;
        if(!$userInfo = parent::getCookieUserInfo($cookie)){
            return parent::ajaxError('暂无启用自动登录');
        }
        $where = [
            'account' => $userInfo['account'],
            'power' => true
        ];
        $user = Users::where($where)->first();
        if(!$user){
            return parent::ajaxError('用户名或密码错误');
        }
        if($userInfo['password'] !== $user->password){
            return parent::ajaxError('用户名或密码错误');
        } 

        # 存储缓存服务器
        $rs = parent::setWebUserInfo($user,$token);
        if(!$rs){
            return parent::ajaxError('服务器异常，请联系管理员');
        }
        # 设置SESSION
        $request->session()->put('ticket', $token);
        return parent::ajaxSuccess($token);
    }
}
