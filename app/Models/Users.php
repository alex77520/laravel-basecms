<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = "tb_users"; # 定义表名
    public $primaryKey = "uid"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
    // 获取关联的第三方用户信息
    public function UserRole(){
        return $this->hasMany("App\Models\UserRole","uid","uid");
    }
    public function UserGroup(){
        return $this->hasOne("App\Models\Groups","uid","uid");
    }
}
