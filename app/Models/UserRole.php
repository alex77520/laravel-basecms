<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = "tb_user_roles"; # 定义表名
    public $primaryKey = "id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    public $timestamps = true;
    public function Roles(){
        return $this->belongsTo("App\Models\Roles","rid","id");
    }
    public function Users(){
        return $this->belongsTo("App\Models\Users","uid","uid");
    }
}
