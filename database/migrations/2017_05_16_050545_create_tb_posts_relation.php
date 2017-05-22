<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbPostsRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_posts_relation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pc_id')->unsigned()->comment('文章分类ID');
            $table->integer('post_id')->unsigned()->comment('文章ID');
            $table->boolean('show')->default(true)->comment('同步文章的显示/隐藏');
            $table->boolean('status')->default(true)->comment('同步文章的禁用/启用');
            $table->integer('top_time')->default(0)->comment('置顶时间');
            $table->timestamps();
            $table->softDeletes();

            // 文章分类外键
            $table->foreign('pc_id')
                  ->references('pc_id')->on('tb_posts_classify')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // 文章外键
            $table->foreign('post_id')
                  ->references('post_id')->on('tb_posts')
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
        Schema::dropIfExists('tb_posts_relation');
    }
}
