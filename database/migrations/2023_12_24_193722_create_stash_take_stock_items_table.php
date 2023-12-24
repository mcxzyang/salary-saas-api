<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStashTakeStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stash_take_stock_items', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('stash_take_stock_id')->comment('所属盘点');
            $table->integer('product_id')->comment('商品ID');
            $table->integer('product_sku_id')->comment('商品 SKU ID');
            $table->integer('stock_in_stash')->nullable()->comment('仓库中的库存');
            $table->integer('stock_check')->nullable()->comment('盘点的库存');
            $table->string('remark')->nullable()->comment('备注');
            $table->integer('operate_by')->nullable()->comment('操作人');
            $table->integer('status')->nullable()->comment('状态');
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
        Schema::dropIfExists('stash_take_stock_items');
    }
}
