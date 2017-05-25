<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_clients', function (Blueprint $table) {
            $table->string('client_id','100')->uniqid()->comment('客户端ID');
            $table->string('client_secret','100')->comment('密钥');
            $table->string('private_key')->comment('私钥');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('client_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_clients');
    }
}
