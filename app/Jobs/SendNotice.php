<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Notices;
use App\Models\Groups;
use App\Models\Users;
use App\Models\NoticeRelation;

class SendNotice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * 通知的主体信息
     *
     * @var string
     **/
    protected $notice;
    /**
     * 数据，可选[users,groups]
     *
     * @var string
     **/
    protected $data;
    /**
     * 类型，可选[users,groups]
     *
     * @var string
     **/
    protected $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Notices $Notices,$data,$type = "users")
    {
        $this->type = $type;
        $this->notice = $Notices;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->type === 'users'){
            $this->UserNotice($this->data);
        }else if($this->type === 'groups'){
            $this->GroupNotice();
        }else if($this->type === 'normal'){
            $this->NormalNotice();
        }
    }
    /**
     * 执行用户数据通知
     *
     * @param type var Description
     **/
    private function UserNotice($users){
        foreach($users as $user){
            $Relation = new NoticeRelation;
            $Relation->notice_id = $this->notice->notice_id;
            $Relation->uid = $user;
            $Relation->save();
        }
    }
    /**
     * 分组的用户关系处理
     *
     * @param type var Description
     **/
    private function GroupNotice(){
        foreach($this->data as $group){
            # 获取分组信息
            $group = Groups::where('gid',$group)->first();
            $users = [];
            if($group != null){
                $users[] = $group->uid;
                if(count($group->GroupUsers) >= 1){
                    foreach($group->GroupUsers as $Users){
                        $users[] = $Users->uid;
                    }
                }
            }
            # 检测数组里是不是有用户，如果没得用户就不白费力气了
            if(count($users) > 0){
                $this->UserNotice($users);
            }
        }
    }
    /**
     * 全部用户群发
     *
     * @param type var Description
     **/
    private function NormalNotice()
    {
        $Users = Users::all();
        $users = [];
        foreach($Users as $User){
            $users[] = $User->uid;
        }
        $this->UserNotice($users);
    }
}
