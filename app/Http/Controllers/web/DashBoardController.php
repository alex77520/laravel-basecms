<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

use App\Models\NoticeRelation;

class DashBoardController extends Controller
{
    public function __construct(){
        parent::__construct();
    }
    public function index(){
        # 获取最近的小组十条日志记录

        # 获取所有用户列表和昨天的用户新增
        // $yesterday = date('Y-m-d',time()-3600*24);
        // $yesCnt = Users::where('created_at','>',$yesterday)->count();
        // $allCnt = Users::count();
        // $data = [
        //     ''
        // ];
        $NoticeRelation = NoticeRelation::where('uid',self::$user['uid'])->offset(0)->limit(10)->get();
        $data = [
            'notices' => $NoticeRelation
        ];
        return view('web/v1/admin/dashboard' ,$data);
    }
    public function getUnreadMsgCount(Request $request){
    }
}
