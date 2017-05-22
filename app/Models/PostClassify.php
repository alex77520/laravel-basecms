<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostClassify extends Model
{
    protected $table = "tb_posts_classify"; # 定义表名
    public $primaryKey = "pc_id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;
    public function parent()
    {
        return $this->hasOne('App\Models\PostClassify', 'pc_id' , 'father');
    }

    public function children()
    {
        return $this->hasMany('App\Models\PostClassify', 'father', 'pc_id' );
    }

}
