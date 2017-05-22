<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

use App\Models\Users;
use App\Models\Resources;
use App\Http\Requests\UserInfoEditPost;
use App\Http\Requests\UserInfoPasswordPost;
use Hash;
use Cookie;

class UcenterController extends Controller
{
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        return view('web.v1.admin.ucenter.index');
    }
    /**
     * 用户信息修改
     *
     * 用户个人修改自己的身份信息
     *
     * @param type var Description
     **/
    public function userInfoEdit(Request $request)
    {
        $User = Users::where('uid',self::$user['uid'])->first();
        if(!$User){
            return parent::_error('用户信息不存在，请退出重新登录');
        }
        $data = [
            'user' => $User
        ];
        return view('web.v1.admin.ucenter.user.edit',$data);
    }
    /**
     * 修改用户信息[Action]
     *
     * 执行用户信息的修改，必须添加头像
     *
     * @param type var Description
     **/
    public function doUserInfoEdit(UserInfoEditPost $request)
    {
        $UserInfo = Users::where('uid',self::$user['uid'])->where('power',true)->first();
        if(!$UserInfo){
            return parent::ajaxError('用户不存在，请重新登录');
        }
        $UserInfo->nickname = $request->input('nickname');
        $UserInfo->email = $request->input('email');
        if($request->has('intro')){
            $UserInfo->intro = $request->input('intro');
        }
        if($request->has('avatar')){
            # 头像存储真实的URL地址
            $Resource = Resources::where('id',$request->input('avatar'))->first();
            if(!$Resource){
                return parent::ajaxError('选择的头像资源不存在');
            }
            if(!in_array($Resource->type,['png','jpg','gif','jpeg','bmp'])){
                return parent::ajaxError('请选择正确的图片文件');
            }
            $UserInfo->avatar = parent::ResourcePath($Resource->path,$Resource->filename);
        }
        if(!$UserInfo->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            # 更改缓存服务器
            parent::resetWebUserInfo($UserInfo);
            return parent::ajaxSuccess('保存成功');
        }
    }
    /**
     * 密码修改
     *
     * @param type var Description
     **/
    public function password(Request $request)
    {
        return view('web.v1.admin.ucenter.user.password');
    }
    /**
     * 修改密码[Action]
     *
     * 执行密码的修改操作
     *
     * @param type var Description
     **/
    public function doPassword(UserInfoPasswordPost $request)
    { 
        $User = Users::where('uid',self::$user['uid'])->first();
        if(!$User){
            return parent::ajaxError('用户信息不存在，请退出重新登录');
        }
        $oldpassword = $request->input('oldpassword');
        if(!Hash::check($oldpassword, $User->password)){
            return parent::ajaxError('原密码不正确');
        }
        $User->password = Hash::make($request->input('password'));
        if(!$User->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            # 密码都修改了，肯定是要注销了
            $request->session()->forget('ticket');
            Cookie::queue('app.vcomer.cn_autologin_key',null,-1);
            return parent::ajaxSuccess('保存成功');
        }
    }
}
