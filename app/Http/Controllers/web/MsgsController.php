<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

use App\Models\NoticeRelation;

class MsgsController extends Controller
{
    /**
     * 消息列表
     *
     * @param type var Description
     **/
    public function index(Request $request)
    {
        $Msgs = NoticeRelation::where('uid',self::$user['uid'])->orderBy('updated_at','desc')->paginate(20);
        $data = [
            'msgs' => $Msgs
        ];
        return view('web.v1.admin.msg.user.index', $data);
    }
    /**
     * 消息详情
     *
     * @param type var Description
     **/
    public function intro(Request $request)
    {
        if(!$request->has('id')){
            return parent::_error('操作异常，请返回重试');
        }
        $NoticeRelation = NoticeRelation::where('id',$request->input('id'))->first();
        if(!$NoticeRelation){
            return parent::_error('操作异常，请刷新重试');
        }
        if($NoticeRelation->is_visit != '1'){
            $NoticeRelation->is_visit = true;
            $NoticeRelation->visit_time = time();
            $NoticeRelation->save();
        }
        $data = [
            'notice' => $NoticeRelation->Notice
        ];
        return view('web.v1.admin.msg.intro', $data);
    }

    /**
     * 批量删除
     *
     * @param type var Description
     **/
    public function doDels(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $updateRows = NoticeRelation::whereIn('id',$ids)->where(['uid'=>self::$user['uid']])->update(['deleted_at'=>date('Y-m-d H:i:s',time())]);
        // parent::GroupLogger('update',$request->input('ids'),"批量删除通知,ID:{".$request->input('ids')."}","MSG");        
        return parent::ajaxSuccess($updateRows);
    }
    /**
     * 批量标记已读
     *
     * @param type var Description
     **/
    public function doReads(Request $request)
    {
        if(!$request->has('ids')){
            return parent::ajaxError('操作异常，请刷新重试');
        }
        $ids = explode(",",$request->input('ids'));
        $updateRows = NoticeRelation::whereIn('id',$ids)->where(['uid'=>self::$user['uid'],'is_visit'=>false])->update(['is_visit'=>true,'visit_time'=>time()]);
        // parent::GroupLogger('update',$request->input('ids'),"批量标记已读,ID:{".$request->input('ids')."}","MSG");        
        return parent::ajaxSuccess($updateRows);
    }
}
