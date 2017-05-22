<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbNoticesRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_notices_relation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('notice_id')->unsigned()->comment('消息主体');
            $table->integer('uid')->unsigned()->comment('接收者');
            $table->boolean('is_visit')->default(false)->comment('未读已读');
            $table->integer('visit_time')->default(0)->comment('已读时间');
            $table->timestamps();
            $table->softDeletes();

            // 用户外键
            $table->foreign('uid')
                  ->references('uid')->on('tb_users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            // 消息外键
            $table->foreign('notice_id')
                  ->references('notice_id')->on('tb_notices')
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
        Schema::dropIfExists('tb_notices_relation');
    }
}
