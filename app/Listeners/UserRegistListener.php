<?php

namespace App\Listeners;

use App\Events\UserRegist;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

use App\Models\Groups;
use App\Models\ResourceClassify;

class UserRegistListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegist  $event
     * @return void
     */
    public function handle(UserRegist $event)
    {
        // 初始化分组
        $User = $event->user;
        $Groups = new Groups;
        $Groups->gid = 'app_'.str_random(28);
        $Groups->uid = $User->uid;
        $Groups->name = $User->account."的分组";
        $Groups->secret = str_random(78);
        $initResult = $Groups->save();
        if(!$initResult){
            Log::error('初始化用户'.$User->account.'时，创建分组失败',['uid'=>$User->uid]);
        }else{
            Log::info('初始化用户'.$User->account.' [创建分组]成功！',['uid'=>$User->uid]);
        }
        // 初始化文件分类
        $ResourceClassify = new ResourceClassify;
        $ResourceClassify->name = '默认分类';
        $ResourceClassify->sort = 0;
        $ResourceClassify->gid = $Groups->gid;
        $resourceResult = $ResourceClassify->save();
        if(!$resourceResult){
            Log::error('初始化用户'.$User->account.'时，创建默认分类失败',['uid'=>$User->uid]);
        }else{
            Log::info('初始化用户'.$User->account.' [创建默认分类]成功！',['uid'=>$User->uid]);
        }
    }
}
