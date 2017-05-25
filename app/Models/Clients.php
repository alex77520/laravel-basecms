<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $table = "tb_clients"; # 定义表名
    public $primaryKey = "client_id"; # 定义主键
    public $incrementing = false; # false代表非自增，默认有id且为int
    public $timestamps = true;
}
