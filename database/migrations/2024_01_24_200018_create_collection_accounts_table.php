<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionAccountsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return  void
    */
    public function up()
    {
        Schema::create('collection_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('name')->comment('账户名称');
            $table->string('account_no')->comment('账户信息');
            $table->decimal('income_amount', 10)->nullable()->comment('收入金额');
            $table->decimal('out_amount', 10)->nullable()->comment('支出金额');
            $table->string('qr_code_image')->nullable()->comment('二维码');
            $table->integer('status')->default(1);

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
        Schema::drop('collection_accounts');
    }
};
