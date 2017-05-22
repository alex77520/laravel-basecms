<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbGroupsUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_groups_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gid')->comment('小组ID');
            $table->integer('uid')->unsigned()->comment('用户ID');
            $table->timestamps();

            // 分组外键
            $table->foreign('gid')
                  ->references('gid')->on('tb_groups')
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
        Schema::dropIfExists('tb_groups_user');
    }
}
