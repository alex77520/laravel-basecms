<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\web\Controller;

class CommonController extends Controller
{
    public function error(Request $request){
        if(!$request->has('msg')){
            $msg = '资源不存在或权限不足';
        }else{
            $msg = $request->get('msg');
        }
        return view('web.v1.error.page' , ['title'=>$msg]);
    }
    public function error_modal(Request $request){
        if(!$request->has('msg')){
            $msg = '资源不存在或权限不足';
        }else{
            $msg = $request->get('msg');
        }
        return view('web.v1.error.modal' , ['title'=>$msg]);
    }
}
