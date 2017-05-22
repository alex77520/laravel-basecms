<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $table = "tb_groups"; # 定义表名
    public $primaryKey = "gid"; # 定义主键
    public $incrementing = false; # false代表非自增，默认有id且为int
    public $timestamps = true;
    public function GroupUsers(){
        return $this->hasMany('App\Models\GroupUser','gid','gid');
    }
    public function User(){
        return $this->belongsTo('App\Models\Users','uid','uid');
    }
}
