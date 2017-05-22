<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = "tb_roles"; # 定义表名
    public $primaryKey = "id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    public $timestamps = true;
}
