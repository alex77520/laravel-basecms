<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 用户角色关系表
         *
         */
        Schema::create('tb_user_roles', function (Blueprint $table) {
            $table->increments('id')->comment('关系ID');
            $table->integer('rid')->unsigned()->comment('角色ID');
            $table->integer('uid')->unsigned()->comment('用户ID');
            $table->timestamps();
            // 分组外键
            $table->foreign('rid')
                  ->references('id')->on('tb_roles')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            // 用户外键
            $table->foreign('uid')
                  ->references('uid')->on('tb_users')
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
        Schema::dropIfExists('tb_user_roles');
    }
}
