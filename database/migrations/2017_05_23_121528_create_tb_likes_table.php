<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mid')->unsigned()->comment('会员ID');
            $table->boolean('is_post')->default(true)->comment('默认类型文章');
            $table->integer('post_id')->unsigned()->nullable()->comment('文章ID');
            $table->integer('comment_id')->unsigned()->nullable()->comment('评论ID');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('mid')
                  ->references('mid')
                  ->on('tb_members')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('post_id')
                  ->references('post_id')
                  ->on('tb_posts')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('comment_id')
                  ->references('comment_id')
                  ->on('tb_comments')
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
        Schema::dropIfExists('tb_likes');
    }
}
