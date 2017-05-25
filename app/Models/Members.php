<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    protected $table = "tb_members"; # 定义表名
    public $primaryKey = "mid"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    public $timestamps = true;
}
