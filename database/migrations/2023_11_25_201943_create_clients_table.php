<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('type')->comment('类型');
            $table->string('mini_program_app_id')->nullable()->comment('微信小程序 app id');
            $table->string('mini_program_app_secret')->nullable()->comment('微信小程序 app secret');
            $table->string('wechat_pay_app_id')->nullable()->comment('微信支付 app id');
            $table->string('wechat_pay_app_mchid')->nullable()->comment('微信支付 mchid');
            $table->string('wechat_pay_key')->nullable()->comment('微信支付 key');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('clients');
    }
}
