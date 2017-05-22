<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

use App\Models\Notices;
use App\Models\Users;
use App\Models\Groups;
use App\Models\GroupUser;
use App\Models\NoticeRelation;

use App\Http\Requests\NoticePost;
use App\Jobs\SendNotice;
class MsgController extends Controller
{
    public function index(Request $request){
        $where = []; # 查询的条件
        $key = null; # 存储搜索KEY的条件，因为要匹配3个不同字段，所以要用or
        $search = []; # 存储搜索的条件，用于展示
        if($request->has('uid')){
            if($request->get('uid') != '0'){
                $where['uid'] = $request->get('uid');
            }
            $search['uid'] = $request->get('uid');
        }
        if($request->has('to')){
            $search['to'] = $request->get('to');
            switch ($request->get('to')) {
                case '1':
                    $where['to'] = true;
                    break;
                case '2':
                    $where['to'] = false;
                    break;
            }
        }
        if($request->has('key')){
            $search['key'] = $request->get('key');
            $key = $request->get('key');
        }
        if($request->has('level')){
            if($request->get('level') != '0'){
                $where['level'] = $request->get('level');
            }
            $search['level'] = $request->get('level');
        }
        if($key == null){
            $Notices = Notices::where($where)->orderBy('updated_at','desc')->paginate(15);
        }else{
            $Notices = Notices::where($where)
                        ->where(function($query) use ($key){
                            $query->orWhere('title','like','%'.$key.'%')->orWhere('content','like','%'.$key.'%');
                        })->orderBy('updated_at','desc')->paginate(15);
        }
        $Users = Users::all();
        $data = [
            'users' => $Users,
            'notices' => $Notices,
            'search' => $search
        ];
        return view('web.v1.admin.msg.admin.index', $data);
    }
    /**
     * 通知详情
     *
     * @param type var Description
     **/
    public function intro(Request $request)
    {
        if(!$request->has('id')){
            return parent::_error('操作异常，请返回重试');
        }
        $Notice = Notices::where('notice_id',$request->input('id'))->first();
        if(!$Notice){
            return parent::_error('当前通知不存在');
        }
        $data = [
            'notice' => $Notice
        ];
        return view('web.v1.admin.msg.intro', $data);
    }
    /**
     * 通知用户的已读/未读情况
     *
     * @param type var Description
     **/
    public function intro_users(Request $request)
    {
        if(!$request->has('id')){
            return parent::_error('操作异常，请返回重试');
        }
        $Notice = Notices::where('notice_id',$request->input('id'))->first();
        if(!$Notice){
            return parent::_error('当前通知不存在');
        }
        $data = [
            'notice' => $Notice
        ];
        return view('web.v1.admin.msg.users', $data);
    }
    public function create(Request $request){
        $data = [];
        if($request->has('type')){
            $data = [
                'groups' => Groups::all(),
                'type' => $request->input('type')
            ];
        }
        return view('web.v1.admin.msg.admin.create', $data);
    }
    /**
     * 发送站内信[所有]
     *
     * @param type var Description
     **/
    public function doCreate(NoticePost $request){
        $Notices = new Notices;
        $Notices->title = $request->input('title');
        $Notices->content = $request->input('content');
        $Notices->type = false; # 非群发
        $Notices->level = $request->input('type');
        $Notices->to = true; # 站点消息
        $Notices->uid = self::$user['uid'];
        if(!$Notices->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 后台Job跑起来
        $job = (new SendNotice($Notices,[],'normal'))->onQueue('notices');
        dispatch($job);

        # 返回成功的操作
        return parent::ajaxSuccess('推送成功');
    }
    /**
     * 发送站内信[选择部分群组]
     *
     * @param type var Description
     **/
    public function doCreateGroup(NoticePost $request){
        if(!$request->has('group')){
            return parent::ajaxError('请选择通知范围');
        }
        $groups = $request->input('group');
        if(!is_array($groups)){
            return parent::ajaxError('请选择正确的通知范围');
        }
        $Notices = new Notices;
        $Notices->title = $request->input('title');
        $Notices->content = $request->input('content');
        $Notices->type = false; # 非群发
        $Notices->level = $request->input('type');
        $Notices->to = true; # 站点消息
        $Notices->uid = self::$user['uid'];
        if(!$Notices->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 后台Job跑起来
        $job = (new SendNotice($Notices,$groups,'groups'))->onQueue('notices');
        dispatch($job);

        # 返回成功的操作
        return parent::ajaxSuccess('推送成功');
    }
    /**
     * 发送站内信[选择部分用户]
     *
     * @param type var Description
     **/
    public function doCreateUser(NoticePost $request){
        if(!$request->has('user')){
            return parent::ajaxError('请选择通知范围');
        }
        $users = $request->input('user');
        if(!is_array($users)){
            return parent::ajaxError('请选择正确的通知范围');
        }
        $Notices = new Notices;
        $Notices->title = $request->input('title');
        $Notices->content = $request->input('content');
        $Notices->type = false; # 非群发
        $Notices->level = $request->input('type');
        $Notices->to = true; # 站点消息
        $Notices->uid = self::$user['uid'];
        if(!$Notices->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 后台Job跑起来
        $job = (new SendNotice($Notices,$users,'users'))->onQueue('notices');
        dispatch($job);

        # 返回成功的操作
        return parent::ajaxSuccess('推送成功');
    }
    /**
     * 获取未读的消息，最多8条
     *
     * 为Dashboard首页开放
     *
     * @param type var Description
     **/
    public function getUnreadMsg(Request $request){
        if(!$request->has('pagesize')){
            $size = 8;
        }else{
            $size = $request->input('pagesize');
            if(!is_numeric($size)){
                $size = 8;
            }
        }
        $NoticeRelation = NoticeRelation::where('uid',self::$user['uid'])->offset(0)->limit($size)->get();
        $resp = [];
        foreach($NoticeRelation as $Notice){
            switch ($Notice->Notice->level) {
                case '2':
                    $level = '<span class="text-primary">[重要]</span>';
                    break;
                case '3':
                    $level = '<span class="text-danger">[紧急]</span>';
                    break;
                
                default:
                    $level = '<span class="text-dark">[一般]</span>';
                    break;
            }
            if($Notice->to == '1'){
                $to = '[站点]';
            }else{
                $to = '[小组]';
            }
            $resp[] = [
                'msg_id' => $Notice->notice_id,
                'level' => $to.$level,
                'title' => $Notice->Notice->title,
                'time' => $Notice->Notice->created_at->format('m-d H:i'),
                'is_visit' => $Notice->is_visit
            ];
        }
        return parent::ajaxSuccess($resp);
    }
    /**
     * 批量删除通知
     *
     * @param type var Description
     **/
    public function doDelNotices(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $deleteRows = Notices::whereIn('notice_id',$ids)->delete();
        return parent::ajaxSuccess($deleteRows);
    }
    /**
     * 单条删除通知
     *
     * @param type var Description
     **/
    public function doDelNotice(Request $request)
    {
        if(!$request->has('notice_id')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $deleteRows = Notices::where('notice_id',$request->input('notice_id'))->delete();
        return parent::ajaxSuccess($deleteRows);
    }





    public function organIndex(Request $request){
        $where = ['uid'=>self::$user['uid'],'to'=>false]; # 查询的条件
        $key = null; # 存储搜索KEY的条件，因为要匹配3个不同字段，所以要用or
        $search = []; # 存储搜索的条件，用于展示
        if($request->has('level')){
            if($request->get('level') != '0'){
                $where['level'] = $request->get('level');
            }
            $search['level'] = $request->get('level');
        }
        if($request->has('key')){
            $search['key'] = $request->get('key');
            $key = $request->get('key');
        }
        if($key == null){
            $Notices = Notices::where($where)->orderBy('updated_at','desc')->paginate(15);
        }else{
            $Notices = Notices::where($where)
                        ->where(function($query) use ($key){
                            $query->orWhere('title','like','%'.$key.'%')->orWhere('content','like','%'.$key.'%');
                        })->orderBy('updated_at','desc')->paginate(15);
        }
        $data = [
            'notices' => $Notices,
            'search' => $search
        ];
        return view('web.v1.admin.msg.index', $data);
    }
    /**
     * 通知详情
     *
     * @param type var Description
     **/
    public function organIntro(Request $request)
    {
        if(!$request->has('id')){
            return parent::_error('操作异常，请返回重试');
        }
        $where = [
            'notice_id' => $request->input('id'),
            'uid' => self::$user['uid'],
            'to' => false
        ];
        $Notice = Notices::where($where)->first();
        if(!$Notice){
            return parent::_error('当前通知不存在');
        }
        $data = [
            'notice' => $Notice
        ];
        return view('web.v1.admin.msg.intro', $data);
    }
    /**
     * 通知用户的已读/未读情况
     *
     * @param type var Description
     **/
    public function organIntroUsers(Request $request)
    {
        if(!$request->has('id')){
            return parent::_error('操作异常，请返回重试');
        }
        $where = [
            'notice_id' => $request->input('id'),
            'uid' => self::$user['uid'],
            'to' => false
        ];
        $Notice = Notices::where($where)->first();
        if(!$Notice){
            return parent::_error('当前通知不存在');
        }
        $data = [
            'notice' => $Notice
        ];
        return view('web.v1.admin.msg.users', $data);
    }
    public function organCreate(Request $request){
        $data = [
            'group' => Groups::where('gid',self::$user['gid'])->first()
        ];
        if($request->has('type')){
            $data['type'] = $request->input('type');
        }
        return view('web.v1.admin.msg.create', $data);
    }
    /**
     * 发送组内信
     *
     * @param type var Description
     **/
    public function doOrganCreate(NoticePost $request){
        $Notices = new Notices;
        $Notices->title = $request->input('title');
        $Notices->content = $request->input('content');
        $Notices->type = true; # 非群发
        $Notices->level = $request->input('type');
        $Notices->to = false; # 组内消息
        $Notices->uid = self::$user['uid'];
        if(!$Notices->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }
        # 后台Job跑起来
        $job = (new SendNotice($Notices,[self::$user['gid']],'groups'))->onQueue('notices');
        dispatch($job);
        parent::GroupLogger('insert',$Notices->notice_id,"发布通知：".$Notices->title,"NOTICE");
        # 返回成功的操作
        return parent::ajaxSuccess('推送成功');
    }
    /**
     * 发送站内信[选择部分用户]
     *
     * @param type var Description
     **/
    public function doOrganCreateUser(NoticePost $request){
        if(!$request->has('user')){
            return parent::ajaxError('请选择通知范围');
        }
        $users = $request->input('user');
        if(!is_array($users)){
            return parent::ajaxError('请选择正确的通知范围');
        }
        $Notices = new Notices;
        $Notices->title = $request->input('title');
        $Notices->content = $request->input('content');
        $Notices->type = false; # 非群发
        $Notices->level = $request->input('type');
        $Notices->to = false; # 组内消息
        $Notices->uid = self::$user['uid'];
        if(!$Notices->save()){
            return parent::ajaxError('服务器异常，请稍后重试');
        }

        # 做筛选，防止页面随便加用户ID，验证传过来的ID是不是都是该组的，移除非该组的ID

        $trueUsers = []; # 最终决定要通知的用户
        $GroupUsers = GroupUser::where('gid',self::$user['gid'])->get();
        if($GroupUsers != null){
            foreach($GroupUsers as $GroupUser){
                if(in_array($GroupUser->uid,$users)){
                    $trueUsers[] = $GroupUser->uid;
                }
            }
        }
        if(in_array(self::$user['uid'],$users)){
            $trueUsers[] = self::$user['uid'];
        }

        # 后台Job跑起来
        $job = (new SendNotice($Notices,$trueUsers,'users'))->onQueue('notices');
        dispatch($job);
        parent::GroupLogger('insert',$Notices->notice_id,"发布通知：".$Notices->title,"NOTICE");
        # 返回成功的操作
        return parent::ajaxSuccess('推送成功');
    }
    /**
     * 批量删除通知
     *
     * @param type var Description
     **/
    public function doOrganDelNotices(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $deleteRows = Notices::whereIn('notice_id',$ids)->where(['uid'=>self::$user['uid'],'to'=>false])->delete();
        parent::GroupLogger('delete',$request->input('ids'),"批量删除通知,ID:{".$request->input('ids')."}","NOTICE");        
        return parent::ajaxSuccess($deleteRows);
    }
    /**
     * 单条删除通知
     *
     * @param type var Description
     **/
    public function doOrganDelNotice(Request $request)
    {
        if(!$request->has('notice_id')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $Notice = Notices::where('notice_id',$request->input('notice_id'))->where(['uid'=>self::$user['uid'],'to'=>false])->first();
        if(!$Notice){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $Notice->delete();
        parent::GroupLogger('delete',$Notice->notice_id,"删除通知：".$Notice->title,"NOTICE");
        return parent::ajaxSuccess($deleteRows);
    }
}
