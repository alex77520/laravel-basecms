<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = "tb_posts"; # 定义表名
    public $primaryKey = "post_id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    public function ClassifyRelation(){
        return $this->hasMany('App\Models\PostRelation','post_id','post_id');
    }
    public function Content(){
        return $this->hasOne('App\Models\PostContent','post_id','post_id');
    }
    public function Contents(){
        return $this->hasMany('App\Models\PostContent','post_id','post_id');
    }
    public function Group(){
        return $this->belongsTo('App\Models\Groups','gid','gid');
    }
}
