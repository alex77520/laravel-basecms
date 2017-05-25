<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbMomentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_moments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->uniqid()->comment('键名');
            $table->integer('mid')->unsigned()->comment('会员ID');
            $table->integer('time')->default(0)->comment('最后操作时间');
            $table->timestamps();
            $table->foreign('mid')
                  ->references('mid')
                  ->on('tb_members')
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
        Schema::dropIfExists('tb_moments');
    }
}
