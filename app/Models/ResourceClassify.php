<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ResourceClassify extends Model
{
    protected $table = "tb_resources_classify"; # 定义表名
    public $primaryKey = "id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    public $timestamps = true;
    public function Resources(){
        return $this->hasMany("App\Models\Resources","cid","id");
    }
}
