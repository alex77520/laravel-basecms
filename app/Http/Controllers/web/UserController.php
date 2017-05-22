<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

use App\Models\Users;
use App\Models\Roles;
use App\Models\UserRole;
use App\Http\Requests\RoleEditPost;
use App\Http\Requests\UserEditPost;
use Hash;

class UserController extends Controller
{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 用户列表
     *
     * 展示用户列表，每页9条
     *
     * @param type var Description
     **/
    public function index(Request $request){
        $s = null;
        if($request->has('s')){
            $s = trim($request->input('s'));
            $users = Users::where('account','like','%'.$s.'%')
                            ->orWhere('nickname','like','%'.$s.'%')
                            ->orWhere('email','like','%'.$s.'%')
                            ->paginate(9);
        }else{
            $users = Users::paginate(9);
        }
        if($users != null){
            foreach($users as $key=>$user){
                $list = [];
                foreach($user->UserRole as $UserRole){
                    $list[] = $UserRole->Roles->name;
                }
                $users[$key]->Role = $list;
            }
        }
        $data = [
            's' => $s,
            'users' => $users,
        ];
        return view('web.v1.admin.user.userman',$data);
    }
    /**
     * 修改用户资料
     *
     * @param type var Description
     **/
    public function edit(Request $request)
    {
        $id = $request->get('id');
        $User = Users::where('uid',$id)->first();
        if(!$User){
            return parent::_error('用户信息不存在');
        }
        $Roles = Roles::all(); # 获取所有的角色
        $UserRoles = UserRole::where('uid' , $id)->get(); # 查看该用户拥有的角色
        $HaveRoles = [];
        foreach($UserRoles as $temp){
            $HaveRoles[] = $temp->rid; # 将角色依次添加进用户拥有的角色列表中
        }

        $data = [
            'user' => $User,
            'roles' => $Roles,
            'userRole' => $HaveRoles
        ];
        return view('web.v1.admin.user.useredit', $data);
    }
    /**
     * 修改用户资料[Action]
     *
     * 管理员执行用户资料的修改，除了头像外的其他资料均可修改。
     *
     * @param type var Description
     **/
    public function doEdit(UserEditPost $request)
    {
        if(!$request->has('uid')){
            return parent::ajaxError('操作异常，请刷新页面重试');
        }
        $uid = $request->input('uid');
        $User = Users::where('uid',$uid)->first();
        if(!$User){
            return parent::ajaxError('用户信息不存在');
        }
        $User->nickname = $request->input('nickname');
        $User->email = $request->input('email');
        $User->power = $request->input('power') == '1' ? true : false;
        if($request->has('password')){
            $password = $request->input('password');
            if(strlen($password) < 8 || strlen($password) > 20){
                return parent::ajaxError('密码长度应为8 - 20位');
            }
            if($request->input('password') !== $request->input('repassword')){
                return parent::ajaxError('两次密码输入不一致');
            }
            $User->password = Hash::make($password);
        }
        if($request->has('intro')){
            $intro = $request->input('intro');
            if(strlen($intro) < 1 || strlen($intro) > 100){
                return parent::ajaxError('简介长度应为1 - 100字');
            }
            $User->intro = $intro;
        }
        $userResult = $User->save();
        if(!$userResult){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 添加角色关系
        if($request->has('roles')){
            $roles = $request->input('roles');
            UserRole::where('uid' , $User->uid)->delete(); # 删除之前的数据
            foreach($roles as $role){
                $UserRole = new UserRole;
                $UserRole->uid = $User->uid;
                $UserRole->rid = $role;
                $UserRole->save();
            }
        }
        return parent::ajaxSuccess('修改成功');
    }
    /**
     * 角色列表
     *
     * 展示角色列表，每页12条
     *
     * @param type var Description
     **/
    public function role(Request $request)
    {
        $s = null;
        if($request->has('s')){
            $s = trim($request->input('s'));
            $roles = Roles::where('name','like','%'.$s.'%')
                            ->orWhere('intro','like','%'.$s.'%')
                            ->paginate(12);
        }else{
            $roles = Roles::paginate(12);
        }
        $limitList = array_dot(parent::limitList());
        foreach($roles as $key=>$role){
            $tempList = array();
            $limits = explode(',',$role['limits']);
            foreach($limits as $list){ # 用户拥有的所有权限
                foreach($limitList as $limitKey=>$limitValue){ # 所有系统权限列表
                    if(strpos($limitKey,$list)){
                        $tempList[] = $limitValue;
                    }
                }
            }
            $roles[$key]['role_listname'] = $tempList;
        }
        $data = [
            's' => $s,
            'roles' => $roles,
        ];
        return view('web.v1.admin.user.roleman',$data);
    }
    /**
     * 添加角色
     *
     * @param type var Description
     **/
    public function roleAdd(Request $request)
    {
        $limitList = parent::limitList();
        $data = [
            'limitlist' => $limitList
        ];
        return view('web.v1.admin.user.roleadd',$data);
    }

    /**
     * 添加角色[Action]
     *
     * 管理员执行用户角色的添加。
     *
     * @param type var Description
     **/
    public function doRoleAdd(RoleEditPost $request){
        $Roles = new Roles;
        $Roles->name  =  $request->get('name');
        $Roles->intro =  $request->get('intro');
        if(!$request->has('limit')){
            return parent::ajaxError('至少勾选一个权限');
        }
        $limit = $request->get('limit');
        $Roles->limits = implode(',',$limit);
        $rs = $Roles->save();
        if(!$rs){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            return parent::ajaxSuccess('添加成功');
        }
    }
    /**
     * 修改角色
     *
     * @param type var Description
     **/
    public function roleEdit(Request $request){
        $id = $request->get('id');
        $role = Roles::where('id' , $id)->first();
        if(!$role){
            return parent::_error('角色信息不存在' , 'modal');
        }
        $role->limits = explode(',',$role->limits);
        $limitList = parent::limitList();
        $data = [
            'role' => $role,
            'limitlist' => $limitList
        ];
        return view('web.v1.admin.user.roleedit',$data);
    }
    /**
     * 修改角色[Action]
     *
     * 管理员执行用户角色中各项信息以及权限的修改。
     *
     * @param type var Description
     **/
    public function doRoleEdit(RoleEditPost $request){
        if(!$request->has('id')){
            return parent::ajaxError('非法操作');
        }
        $role_id = $request->input('id');
        $Roles = Roles::where('id' , $role_id)->first();
        $Roles->name  =  $request->get('name');
        $Roles->intro =  $request->get('intro');
        if(!$request->has('limit')){
            return parent::ajaxError('至少勾选一个权限');
        }
        $limit = $request->get('limit');
        $Roles->limits = implode(',',$limit);
        $rs = $Roles->save();
        if(!$rs){
            return parent::ajaxError('服务器异常，请稍后重试');
        }else{
            return parent::ajaxSuccess('修改成功');
        }
    }
    /**
     * 删除角色[Action]
     *
     * 管理员执行用户角色的删除。
     *
     * @param type var Description
     **/
    public function doRoleDel(Request $request){
        if(!$request->input('id')){
            return parent::ajaxError('操作异常，请刷新页面重试');
        }
        $Role = Roles::where('id' , $request->input('id'))->delete();
        if($Role > 0){
            return parent::ajaxSuccess('已成功移除该角色，角色下包含的用户关系也已解除');
        }
        return parent::ajaxError('服务器异常，请稍后重试');
    }
}
