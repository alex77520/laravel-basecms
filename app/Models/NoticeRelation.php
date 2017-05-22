<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoticeRelation extends Model
{
    use SoftDeletes;
    /**
     * 需要被转换成日期的属性。
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $table = "tb_notices_relation"; # 定义表名
    public $primaryKey = "id"; # 定义主键
    public $incrementing = true; # false代表非自增，默认有id且为int
    public $timestamps = true;
    public function Notice(){
        return $this->belongsTo('App\Models\Notices','notice_id','notice_id');
    }
    public function User(){
        return $this->belongsTo('App\Models\Users','uid','uid');
    }
}
