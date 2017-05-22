<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

use App\Models\Groups;
use App\Models\Users;
use App\Models\GroupUser;
use App\Models\Resources;
use App\Models\GroupLog;
use App\Http\Requests\UserRegistPost;
use App\Http\Requests\UserPasswordPost;
use Hash;

class OrganizeController extends Controller
{
    public function __construct(){
        parent::__construct();
    }
    public function index()
    {
        $Group = Groups::where('gid',self::$user['gid'])->first();
        $data = [
            'group' => $Group
        ];
        return view('web.v1.admin.organize.index',$data);
    }
    public function doEdit(Request $request){
        if(!$request->has('name')){
            return parent::ajaxError('名称不能为空');
        }
        $Group = Groups::where('gid',self::$user['gid'])->first();
        $Group->name = $request->input('name');
        if($request->has('avatar')){
            # 头像存储真实的URL地址
            $Resource = Resources::where('id',$request->input('avatar'))->first();
            if(!$Resource){
                return parent::ajaxError('选择的头像资源不存在');
            }
            if(!in_array($Resource->type,['png','jpg','gif','jpeg','bmp'])){
                return parent::ajaxError('请选择正确的图片文件');
            }
            $Group->avatar = parent::ResourcePath($Resource->path,$Resource->filename);
        }
        if($request->has('intro')){
            $Group->intro = $request->input('intro');
        }
        if(!$Group->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            return parent::ajaxSuccess('保存成功');
        }
    }
    /**
     * 员工管理
     *
     * @param type var Description
     **/
    public function user(Request $request)
    {
        $Group = Groups::where('gid',self::$user['gid'])->first();
        # 获取员工列表
        $Users = GroupUser::where('gid',$Group->gid)->paginate(10);
        $data = [
            'users' => $Users
        ];
        return view('web.v1.admin.organize.user.index', $data);
    }
    /**
     * 员工添加
     *
     * @param type var Description
     **/
    public function userAdd(Request $request)
    {
        $Group = Groups::where('gid',self::$user['gid'])->first();
        $GroupUserCnt = GroupUser::where('gid',self::$user['gid'])->count();
        $data = [
            'group' => $Group,
            'user_cnt' => $GroupUserCnt
        ];
        return view('web.v1.admin.organize.user.add', $data);
    }
    /**
     * 添加员工[Action]
     *
     * @param type var Description
     **/
    public function doUserAdd(UserRegistPost $request)
    {
        $Group = Groups::where('gid',self::$user['gid'])->first();
        $GroupUserCnt = GroupUser::where('gid',self::$user['gid'])->count();
        if($GroupUserCnt >= $Group->user_num){
            return parent::ajaxError('员工人数已达到规定上限');
        }
        $username = $request->input('username');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        # 检测账号是否已被使用
        $isExist = Users::where('account',$username)->first();
        if($isExist){
            return parent::ajaxError('账号已被占用，请更换一个');
        }
        # 检测机构的员工人数是否达到上限
        $User = new Users;
        $User->account = $username;
        $User->email = $email;
        $User->nickname = "[".$Group->name."]".$username;
        $User->password = $password;
        $User->last_login = date('Y-m-d H:i:s',time());
        $registResult = $User->save();
        if($registResult){
            # 添加关系
            $GroupUser = new GroupUser;
            $GroupUser->uid = $User->uid;
            $GroupUser->gid = $Group->gid;
            if(!$GroupUser->save()){
                # 回滚删除创建的用户
                $User->delete();
                return parent::ajaxError('服务器异常，请稍后重试');
            }
            parent::GroupLogger('insert',$User->account,"账号：".$User->account,"USER");
            return parent::ajaxSuccess('注册成功');
        }else{
            return parent::ajaxError('服务器异常，请稍后重试');
        }
    }
    /**
     * 修改密码
     *
     * @param type var Description
     **/
    public function password(Request $request)
    {
        if(!$request->has('uid')){
            return parent::_error('操作异常，请稍后重试','modal');
        }
        # 检测是不是该分组的
        $GroupUser = GroupUser::where('uid',$request->input('uid'))->where('gid',self::$user['gid'])->first();
        if(!$GroupUser){
            return parent::_error('员工信息不存在','modal');
        }
        $User = Users::where('uid',$request->input('uid'))->first();
        if(!$User){
            return parent::_error('员工信息不存在','modal');
        }
        $data = [
            'user' => $User
        ];
        return view('web.v1.admin.organize.user.password', $data);
    }
    /**
     * 修改密码[Action]
     *
     * 执行密码的修改操作
     *
     * @param type var Description
     **/
    public function doPassword(UserPasswordPost $request)
    { 
        if(!$request->has('uid')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        # 检测是不是该分组的
        $GroupUser = GroupUser::where('uid',$request->input('uid'))->where('gid',self::$user['gid'])->first();
        if(!$GroupUser){
            return parent::ajaxError('员工信息不存在');
        }
        $User = Users::where('uid',$request->input('uid'))->first();
        $User->password = Hash::make($request->input('password'));
        if(!$User->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            parent::GroupLogger('update',$User->account,"[".$User->account."]的密码","USER");
            return parent::ajaxSuccess('保存成功');
        }
    }

    /**
     * 禁用/启用账号
     *
     * 根据传过来的ID进行逆向操作。
     *
     * @param type var Description
     **/
    public function doEnable(Request $request)
    {
        if(!$request->has('uid')){
            return parent::ajaxError('操作异常，请稍后重试');
        }
        # 检测是不是该分组的
        $GroupUser = GroupUser::where('uid',$request->input('uid'))->where('gid',self::$user['gid'])->first();
        if(!$GroupUser){
            return parent::ajaxError('员工信息不存在');
        }
        $User = Users::where('uid',$request->input('uid'))->first();
        $User->power = $User->power == '1' ? false : true;
        if(!$User->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            return parent::ajaxSuccess('操作成功');
        }
    }
    /**
     * 日志列表
     *
     * @param type var Description
     **/
    public function log(Request $request)
    {
        if($request->has('account')){
            $Logs = GroupLog::where('account',$request->input('account'))
                        ->where('gid',self::$user['gid'])
                        ->orderBy('updated_at','desc')
                        ->paginate(15);
        }else{
            $Logs = GroupLog::where('gid',self::$user['gid'])
                        ->orderBy('updated_at','desc')
                        ->paginate(15);
        }
        $data = [
            'logs' => $Logs
        ];
        return view('web.v1.admin.organize.log', $data);
    }
    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     **/
    public function logDelete($value='')
    {
        # code...
    }
    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param type var Description
     **/
    public function doLogDeletes(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $deleteRows = GroupLog::whereIn('id',$ids)->where('gid',self::$user['gid'])->delete();
        return parent::ajaxSuccess($deleteRows);
    }
}
