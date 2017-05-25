<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbMembersTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_members_token', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mid')->unsigned()->comment('会员ID');
            $table->string('access_token')->comment('通行证');
            $table->string('refresh_token')->comment('刷新通行证的凭证');
            $table->integer('expire')->default(0)->comment('有效时间');
            $table->string('client_id',100)->comment('APP ID');
            $table->timestamps();
            // 外键
            $table->foreign('client_id')
                  ->references('client_id')
                  ->on('tb_clients')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
                  
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
        Schema::dropIfExists('tb_members_token');
    }
}
