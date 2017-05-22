<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redis;
use Uuid;
use App\Models\UserRole;
use App\Models\Roles;
use App\Models\Groups;
use App\Models\GroupUser;
use App\Models\GroupLog;

class Controller extends BaseController
{
    public static $user = null;
    public static $LogEnt = [
        'USER' => '用户',
        'POSTS' => '文章',
        'RESOURCE' => '文件',
        'NOTICE' => '通知'
    ];
    public function __construct()
    {
        self::$user = self::getWebUserInfo();
    }
    public function setWebUserInfo ( $user , &$token)
    {
        # 设置用户token
        $token = (string)Uuid::generate();
        # 检测一下是否拥有机构
        $Groups = Groups::where('uid',$user->uid)->first();
        if(!$Groups){
            $GroupUser = GroupUser::where('uid',$user->uid)->first();
            if(!$GroupUser){
                return false;
            }
            $gid = $GroupUser->gid;
        }else{
            $gid = $Groups->gid;
        }
        $userInfo = array(
            'uid'  => $user->uid,
            'account' => $user->account,
            'nickname' => $user->nickname,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'intro' => $user->intro,
            'gid' => $gid,
        );
        # 存入redis数据库中
        $rs = Redis::set($token , json_encode($userInfo));
        if($rs){
            # 设置2小时的过期时间
            Redis::expire($token , 7200);
            return true;
        }else{
            return false;
        }
    }
    public function getWebUserInfo () 
    {
        # 设置用户token
        $token = Session::get('ticket');
        $userInfo = Redis::get($token);
        if(!$userInfo){
            return false;
        }else{
            return json_decode($userInfo , true);
        }
    }
    /**
     * 重置身份信息
     *
     * 用户信息变更后，进行身份信息的变更
     *
     * @param type var Description
     **/
    public function resetWebUserInfo($user)
    { 
        $token = Session::get('ticket');

        # 检测一下是否拥有机构
        $Groups = Groups::where('uid',$user->uid)->first();
        if(!$Groups){
            $GroupUser = GroupUser::where('uid',$user->uid)->first();
            if(!$GroupUser){
                return false;
            }
            $gid = $GroupUser->gid;
        }else{
            $gid = $Groups->gid;
        }

        $userInfo = array(
            'uid'  => $user->uid,
            'account' => $user->account,
            'nickname' => $user->nickname,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'intro' => $user->intro,
            'gid' => $gid,
        );
        # 存入redis数据库中
        $rs = Redis::set($token , json_encode($userInfo));
        if($rs){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 系统所有权限数组
     *
     * @param array
     **/
    public function limitList()
    {
        return [
            'UserMan' => [
                'Intro' => '用户管理',
                'List' => [
                    'UserSee' => '查看用户',
                    'UserEdit'=> '修改用户',
                ]
            ],
            'RoleMan' => [
                'Intro' => '角色管理',
                'List'  => [
                    'RoleSee'  => '查看角色',
                    'RoleAdd'  => '添加角色',
                    'RoleEdit' => '修改角色',
                    'RoleDel'  => '删除角色',
                ],
            ],
            'GroupMan' => [
                'Intro' => '机构管理',
                'List'  => [
                    'GroupSee' => '查看机构',
                    'GroupEdit' => '修改机构信息',
                    'GroupSettingLimit' => '权限设置',
                    'GroupSettingSize' => '资源分配',
                    'GroupSettingEnable' => '启用/禁用',
                    'GroupSettingRecommend' => '设置/取消推荐',
                ]
            ],
            'PostClassifyMan' => [
                'Intro' => '文章分类管理',
                'List'  => [
                    'PostClassifySee' => '查看文章分类',
                    'PostClassifyEdit' => '修改分类信息',
                    'PostClassifySetting' => '批量调整分类',
                    'PostClassifyDelete' => '删除分类'
                ]
            ],
            'PostMan' => [
                'Intro' => '文章管理',
                'List'  => [
                    'PostSee' => '查看',
                    'PostUp' => '置顶',
                    'PostDisable' => '显示/隐藏',
                    'PostDisables' => '显示/隐藏(批量)',
                    'PostBan' => '屏蔽/解除屏蔽',
                    'PostBans' => '屏蔽/解除屏蔽(批量)',
                    'PostDelete' => '删除',
                    'PostDelete' => '删除(批量)',
                ]
            ],
            'MsgMan' => [
                'Intro' => '文章管理',
                'List'  => [
                    'MsgSee' => '查看',
                    'MsgEdit' => '发布',
                    'MsgDel' => '删除',
                    'MsgDels' => '删除(批量)',
                ]
            ]
        ];
    }
    /**
     * 判断当前用户是否包含有该权限
     *
     * @param array
     **/
    public function HasLimit($key)
    {
        if(!Session::has('ticket')){
            return false;
        }else{
            $UserInfo = $this->isAdmin();
            if($UserInfo){
                return true;
            }
        }
        $UserInfo = self::getWebUserInfo();
        $list = array();
        $key = strtolower($key);
        $roleList = UserRole::where('uid',$UserInfo['uid'])->get();
        if(count($roleList) < 1){
            return false;
        }
        foreach($roleList as $role){
            $ids[] = $role['id'];
        }
        $RolesList = Roles::find($ids);
        foreach($RolesList as $Roles){
            $array = explode(',',$Roles['limits']);
            foreach($array as $temp){
                array_push($list,strtolower($temp));
            }
        }
        if(in_array($key,$list)){
            return true;
        }
        return false;
    }
    /**
     * 权限过滤器
     *
     * 入参@param ['page','modal','ajax']
     *
     * @param response
     **/
    public function limitFilter($key,$method='page')
    {
        if(!$this->HasLimit($key)){
            switch ($method) {
                case 'page':
                    return $this->_error('您可能打开了一个假页面','page');
                    break;
                
                case 'modal':
                    return $this->_error('您可能打开了一个假页面','modal');
                    break;

                case 'ajax':
                    return parent::ajaxError('当前操作不存在或您暂无权限');
                    break;
            }
        }
    }
    /**
     * 是否是超级管理员
     *
     * @param array
     **/
    public function isAdmin()
    {
        $UserInfo = self::getWebUserInfo();
        if($UserInfo['account'] === 'admin'){
            return $UserInfo;
        }
        return false;
    }
    /**
     * 是否是机构组长
     *
     * @param type var Description
     **/
    public function isGroupAdmin()
    {
        $group = Groups::where('uid',self::$user['uid'])->where('gid',self::$user['gid'])->first();
        if($group != null){
            return true;
        }
        return false;
    }
    /**
     * 验证是否可以发布文章
     *
     * @param type var Description
     **/
    public function groupStatus()
    {
        $Group = Groups::where('gid',self::$user['gid'])->first();
        if($Group->status == '1'){
            return true;
        }
        return false;
    }
    /**
     * 错误页面
     *
     * 入参@param ['page','modal']
     *
     * @param array
     **/
    public function _error($title,$method = "page")
    {
        $data = [
            'title' => $title
        ];
        switch ($method) {
            case 'modal':
                return view('web.v1.error.modal' , $data);
                break;

            default:
                return view('web.v1.error.page' , $data);
                break;
        }
    }
    /**
     * [机构]记录Log
     *
     * 根据传的参数，进行操作记录的日志更新
     *
     * @param type var Description
     **/
    public function GroupLogger($method,$id,$intro,$entity)
    {
        $Log = new GroupLog;
        switch ($method) {
            case 'insert':
                $Log->data_action = '0';
                break;
            case 'update':
                $Log->data_action = '1';
                break;
            case 'delete':
                $Log->data_action = '2';
                break;
        }
        $Log->data_id = $id;
        $Log->data_main = $intro;
        $Log->data_key = $entity;
        $Log->gid = self::$user['gid'];
        $Log->account = self::$user['account'];
        $Log->save();
    }
}
