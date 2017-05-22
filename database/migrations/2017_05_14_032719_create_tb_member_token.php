<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbMemberToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
        * 会员登录信息表
        *
        * 会员所有的登录Token存储于改变，可通过删除该表中的数据以达到强制用户状态失效
        */
        Schema::create('tb_member_token', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mid')->comment('会员ID');
            $table->string('access_token')->comment('通行证');
            $table->string('refresh_token')->comment('刷新通行证的凭证');
            $table->integer('expire')->default(0)->comment('有效时间');
            $table->string('gid',100)->comment('分组ID');
            $table->timestamps();
            // 分组外键
            $table->foreign('gid')
                  ->references('gid')
                  ->on('tb_groups')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
