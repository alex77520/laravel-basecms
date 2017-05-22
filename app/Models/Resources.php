<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Resources extends Model
{
    protected $table = "tb_resources"; # 定义表名
    public $primaryKey = "id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    public $timestamps = true;
    public function Classify(){
        return $this->belongsTo('App\Models\ResourceClassify','cid','id');
    }
}
