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
        return view('web/v1/admin/dashboard');
    }
    public function getUnreadMsgCount(Request $request){
    }
}
