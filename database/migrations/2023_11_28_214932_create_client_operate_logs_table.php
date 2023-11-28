<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientOperateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_operate_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('company_user_id')->nullable()->comment('操作人');
            $table->string('module')->nullable()->comment('所属模块');
            $table->string('content')->nullable()->comment('操作内容');
            $table->string('client_ip')->nullable()->comment('操作IP');
            $table->string('location')->nullable()->comment('操作地点');
            $table->string('browser')->nullable()->comment('浏览器');
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
        Schema::dropIfExists('client_operate_logs');
    }
}
