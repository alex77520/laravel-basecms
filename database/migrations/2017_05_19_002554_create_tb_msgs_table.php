<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbMsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_msgs', function (Blueprint $table) {
            $table->increments('msg_id');
            $table->integer('from_uid')->unsigned()->comment('发送者');
            $table->integer('to_uid')->unsigned()->comment('接收者');
            $table->string('title',50)->comment('标题');
            $table->string('content',200)->comment('内容');
            $table->boolean('is_visit')->default(false)->comment('未读已读');
            $table->integer('visit_time')->default(0)->comment('已读时间');
            $table->timestamps();
            $table->softDeletes();

            // 用户外键
            $table->foreign('to_uid')
                  ->references('uid')->on('tb_users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            // 用户外键
            $table->foreign('from_uid')
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
        Schema::dropIfExists('tb_msgs');
    }
}
