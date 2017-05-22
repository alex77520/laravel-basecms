<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbPostsClassify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_posts_classify', function (Blueprint $table) {
            $table->increments('pc_id');
            $table->string('name',20)->comment('分类名称');
            $table->string('intro',50)->comment('简介');
            $table->string('key',20)->nullable()->comment('区别的KEY键');
            $table->boolean('show')->default(true)->comment('是否可见');
            $table->integer('father')->default(0)->comment('父级ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_posts_classify');
    }
}
