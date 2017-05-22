<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notices extends Model
{
    protected $table = "tb_notices"; # 定义表名
    public $primaryKey = "notice_id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    public $timestamps = true;
    public function User(){
        return $this->belongsTo("App\Models\Users","uid","uid");
    }
    public function Relation(){
        return $this->hasMany('App\Models\NoticeRelation','notice_id','notice_id');
    }
}
