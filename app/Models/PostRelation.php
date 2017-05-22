<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostRelation extends Model
{
    protected $table = "tb_posts_relation"; # 定义表名
    public $primaryKey = "id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
    public function classify()
    {
        return $this->belongsTo('App\Models\PostClassify', 'pc_id' , 'pc_id');
    }

    public function posts()
    {
        return $this->belongsTo('App\Models\Posts', 'post_id', 'post_id' );
    }
}
