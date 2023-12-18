<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockEnterItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_enter_items', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_enter_id');
            $table->integer('company_id');
            $table->integer('product_id');
            $table->integer('product_sku_id');
            $table->integer('number')->comment('入库数量');
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
        Schema::dropIfExists('stock_enter_items');
    }
}
