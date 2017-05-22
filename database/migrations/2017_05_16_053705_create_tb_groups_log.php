<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbGroupsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_groups_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account')->comment('账号');
            $table->string('data_id')->comment('数据ID');
            $table->enum('data_action',['0','1','2'])->default('0')->comment('操作:0增1改2删');
            $table->string('data_main')->comment('数据主要依据');
            $table->string('data_key')->comment('操作实体，来源于代码');
            $table->string('gid',100)->comment('分组ID');
            $table->timestamps();

            // 分类外键
            $table->foreign('gid')
                  ->references('gid')->on('tb_groups')
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
        Schema::dropIfExists('tb_groups_log');
    }
}
