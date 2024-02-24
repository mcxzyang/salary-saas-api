<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasePlanItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_plan_items', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_plan_id');
            $table->integer('goods_id')->comment('产品');
            $table->string('unit')->nullable()->comment('单位');
            $table->integer('number')->nullable()->comment('计划数量');
            $table->integer('order_number')->nullable()->comment('下单数量');
            $table->integer('delivery_number')->nullable()->comment('交货数量');
            $table->timestamp('delivery_at')->nullable()->comment('交货日期');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('purchase_plan_items');
    }
}
