<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    protected $table = "tb_groups_user"; # 定义表名
    public $primaryKey = "id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    public $timestamps = true;
    public function Group(){
        return $this->belongsTo('App\Models\Groups','gid','gid');
    }
    public function User(){
        return $this->belongsTo('App\Models\Users','uid','uid');
    }
}
