<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_comments', function (Blueprint $table) {
            $table->increments('comment_id');
            $table->text('content')->comment('评论内容');
            $table->boolean('markdown')->default(true)->comment('默认Markdown');
            $table->integer('father')->default(0)->comment('父级ID');
            $table->integer('likes')->default(0)->comment('点赞数');
            $table->integer('comments')->default(0)->comment('评论条数');
            $table->integer('mid')->unsigned()->comment('评论者');
            $table->integer('post_id')->unsigned()->comment('文章ID');
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_comments');
    }
}
