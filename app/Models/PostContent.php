<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostContent extends Model
{
    protected $table = "tb_posts_content"; # 定义表名
    public $primaryKey = "pc_id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
    public function post()
    {
        return $this->belongsTo('App\Models\Posts', 'post_id' , 'post_id');
    }
}
