<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('in_charge_company_user_id')->nullable('负责人');
            $table->integer('type')->comment('1-个人客户 2-企业客户');
            $table->string('name')->comment('客户名称');
            $table->integer('sex')->nullable()->comment('1-男 2-女');
            $table->string('city')->nullable()->comment('所在城市');
            $table->string('phone')->comment('手机号');
            $table->integer('customer_status_id')->nullable()->comment('客户状态');
            $table->integer('type_id')->nullable()->comment('客户类别');
            $table->integer('level_id')->nullable()->comment('客户等级');
            $table->integer('source_id')->nullable()->comment('客户来源');
            $table->integer('ripeness_id')->nullable()->comment('客户成熟度');
            $table->integer('industry_id')->nullable()->comment('所属行业');
            $table->text('description')->nullable()->comment('客户介绍');
            $table->string('link_man')->nullable()->comment('联系人');
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
        Schema::dropIfExists('customers');
    }
}
