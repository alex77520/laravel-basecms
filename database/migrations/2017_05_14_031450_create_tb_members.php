<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
        * 会员表
        * 
        */
        Schema::create('tb_members', function (Blueprint $table) {
            $table->increments('mid');
            $table->string('mobile',11)->nullable()->comment('手机号');
            $table->string('password',100)->nullable()->comment('密码');
            $table->string('nickname',50)->nullable()->comment('昵称');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('wechat')->nullable()->comment('微信第三方');
            $table->string('wechat_openid')->nullable()->comment('微信');
            $table->string('qq_openid')->nullable()->comment('QQ');
            $table->string('sina_openid')->nullable()->comment('新浪微博');
            $table->boolean('power')->default(true)->comment('账号状态');   
            $table->timestamps();
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
