<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTbGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_groups', function (Blueprint $table) {
            $table->string('avatar')->nullable()->comment('头像');
            $table->boolean('post_audit')->default(true)->comment('文章是否需要审核,默认需要');
            $table->integer('resource_size')->default(0)->comment('可用文件大小,单位MB');
            $table->boolean('status')->default(true)->comment('分组是否可用');
            $table->string('intro',100)->nullable()->comment('小组简介');
            $table->integer('user_num')->default(0)->comment('除创建者之外的员工数量');
            $table->boolean('recommend')->default(false)->comment('是否推荐');
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
