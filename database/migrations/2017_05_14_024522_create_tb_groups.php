<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
        * 后台用户分组
        * 
        * 后台用户，自注册后应创建一个由该用户为组长的小组，该小组为最根本的依据，举例：
        *   当会员注册的时候，该会员应归于某个用户的分组下，由该分组进行管理该会员信息，其他分组无法操作
        *     文章、图片等资源文件，相同。
        *
        */
        Schema::create('tb_groups', function (Blueprint $table) {
            $table->string('gid',100)->unique();
            $table->integer('uid')->unsigned()->comment('创建者');
            $table->string('name')->nullable()->comment('名称');
            $table->string('secret')->nullable()->comment('密钥');
            $table->string('url')->nullable()->comment('url');
            $table->string('avatar')->nullable()->comment('头像');
            $table->boolean('post_audit')->default(true)->comment('文章是否需要审核,默认需要');
            $table->integer('resource_size')->default(0)->comment('可用文件大小,单位MB');
            $table->boolean('status')->default(true)->comment('分组是否可用');
            $table->string('intro',100)->nullable()->comment('小组简介');
            $table->integer('user_num')->default(0)->comment('除创建者之外的员工数量');
            $table->boolean('recommend')->default(false)->comment('是否推荐');
            $table->timestamps();
            $table->primary('gid');

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
        //
    }
}
