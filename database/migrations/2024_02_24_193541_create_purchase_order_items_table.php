<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_order_id');
            $table->integer('goods_id');
            $table->string('sku')->nullable()->comment('型号规格');
            $table->string('unit')->nullable()->comment('单位');
            $table->integer('order_number')->nullable()->comment('订购数量');
            $table->integer('notify_number')->nullable()->comment('通知数量');
            $table->integer('delivery_number')->nullable()->comment('到货数量');
            $table->decimal('amount')->nullable()->comment('订购金额');
            $table->timestamp('delivery_at')->nullable()->comment('交货日期');
            $table->decimal('delivery_amount')->nullable()->comment('交货金额');
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
        Schema::dropIfExists('purchase_order_items');
    }
}
