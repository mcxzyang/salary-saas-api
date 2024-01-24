<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return  void
    */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('order_id');
            $table->decimal('total', 10)->nullable()->comment('订单本次支付金额（支出时为负）');
            $table->decimal('order_total', 10)->nullable()->comment('订单总金额');
            $table->decimal('order_already_payed', 10)->nullable()->comment('订单已支付金额');
            $table->json('payment_proof')->nullable()->comment('付款凭证，json');
            $table->timestamp('pay_at')->nullable()->comment('付款时间');
            $table->integer('collection_account_id')->nullable()->comment('付款账户');
            $table->string('pay_method')->nullable()->comment('付款方式');
            $table->string('pay_desc')->nullable()->comment('付款备注');
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
        Schema::drop('payments');
    }
};
