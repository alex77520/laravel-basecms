<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_notices', function (Blueprint $table) {
            $table->increments('notice_id');
            $table->string('title',50)->nullable()->comment('标题');
            $table->string('content',200)->nullable()->comment('内容');
            $table->boolean('type')->default(true)->comment('群发/选中部分');
            $table->enum('level',['1','2','3'])->default('1')->comment('紧急程度');
            $table->boolean('to')->default(true)->comment('整站/小组');
            $table->integer('uid')->unsigned()->comment('发送者');
            $table->timestamps();

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
        Schema::dropIfExists('tb_notices');
    }
}
