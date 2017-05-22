<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path')->comment('文件路径');
            $table->string('filename')->comment('随机生成的文件名');
            $table->string('name')->comment('原文件名');
            $table->string('type')->comment('文件类型');
            $table->string('size')->comment('文件大小,B');
            $table->integer('cid')->unsigned()->comment('分类ID');
            $table->timestamps();

            // 分类外键
            $table->foreign('cid')
                  ->references('id')->on('tb_resources_classify')
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
        Schema::dropIfExists('tb_resources');
    }
}
