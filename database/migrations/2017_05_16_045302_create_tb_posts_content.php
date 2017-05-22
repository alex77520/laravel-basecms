<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbPostsContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_posts_content', function (Blueprint $table) {
            $table->increments('pc_id');
            $table->text('content')->nullable()->comment('内容');
            $table->string('picture')->nullable()->comment('图片,1图1文时必填');
            $table->integer('sort')->default(0)->comment('排序，正序排列');
            $table->boolean('show')->default(true)->comment('是否显示，默认显示');
            $table->integer('post_id')->unsigned()->comment('主体ID');
            $table->timestamps();

            // 分类外键
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
        Schema::dropIfExists('tb_posts_content');
    }
}
