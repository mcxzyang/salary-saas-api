<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return  void
    */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('code')->comment('编码');
            $table->string('name')->comment('名称');
            $table->string('short_name')->nullable()->comment('简称');
            $table->string('level')->nullable()->comment('级别');
            $table->string('ripeness')->nullable()->comment('成熟度');
            $table->string('area')->nullable()->comment('所属区域');
            $table->string('address')->nullable()->comment('地址');
            $table->json('linkman_info')->nullable()->comment('联系人信息，json');
            $table->string('bank_info')->nullable()->comment('开户银行信息，json');
            $table->string('tax_no')->nullable()->comment('税号');
            $table->string('remark')->nullable()->comment('备注');
            $table->integer('status')->nullable()->comment('状态');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return  void
    */
    public function down()
    {
        Schema::drop('vendors');
    }
};
