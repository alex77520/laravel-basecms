<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_posts', function (Blueprint $table) {
            $table->increments('post_id');
            $table->string('title',100)->comment('标题');
            $table->string('source')->nullable()->comment('来源');
            $table->boolean('single')->default(true)->comment('单图文/一图一文，默认单图文');
            $table->boolean('markdown')->default(false)->comment('是否是markdown,默认否');
            $table->boolean('show')->default(true)->comment('是否可见,默认可见');
            $table->string('cover')->nullable()->comment('封面图的资源ID，JSON');
            $table->integer('hit')->default(0)->comment('点击量');
            $table->integer('top_time')->default(0)->comment('置顶时间');
            $table->string('tags')->nullable()->comment('标签列表，用逗号隔开');
            $table->boolean('status')->default(true)->comment('审核状态，默认不审核');
            $table->integer('likes')->default(0)->comment('得到的赞数量');
            $table->integer('comments')->default(0)->comment('得到的评论数');
            $table->integer('stars')->default(0)->comment('得到的收藏数');
            $table->integer('interval')->default(0)->comment('定时发布时间，0为不定时');
            $table->string('gid',100)->comment('发布人的分组');
            $table->timestamps();
            $table->softDeletes();


            // 分组外键
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
        Schema::dropIfExists('tb_posts');
    }
}
