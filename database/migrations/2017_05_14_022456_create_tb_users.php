<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_users', function (Blueprint $table) {
            $table->increments('uid')->comment('UID');
            $table->string('account',20)->comment('用户名');
            $table->string('email',100)->nullable()->comment('邮箱,可为空');
            $table->string('password',100)->comment('密码');
            $table->boolean('power')->default(true)->comment('是否禁用');
            $table->string('nickname',20)->nullable()->comment('昵称,可为空');
            $table->string('avatar')->nullable()->comment('头像,可为空');
            $table->string('intro')->nullable()->comment('简介,可为空');
            $table->timestamp('last_login')->comment('最后登录时间');
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
        Schema::dropIfExists('tb_users');
    }
}
