<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
        * 后台用户角色表
        * 
        * 角色表，即将相应的权限给予该角色，再将该角色通过tb_user_roles分发到每个用户身上
        *   表中的limits来源于代码中的各项权限名称，不由数据库存储
        *
        */
        Schema::create('tb_roles', function (Blueprint $table) {
            $table->increments('id')->comment('角色ID');
            $table->string('name',20)->comment('名称');
            $table->string('intro',50)->nullable()->comment('简介');
            $table->text('limits')->comment('权限列表');
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
        Schema::dropIfExists('tb_roles');
    }
}
