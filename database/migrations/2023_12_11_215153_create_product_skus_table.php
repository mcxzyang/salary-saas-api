<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('product_id');
            $table->string('sku_number')->nullable()->unique()->comment('编码');
            $table->string('sku_name')->comment('名称');
            $table->decimal('original_price')->nullable()->comment('原价');
            $table->decimal('sales_price')->nullable()->comment('售价');
            $table->string('unit')->nullable()->comment('单位');
            $table->integer('stock')->nullable()->comment('库存');
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
        Schema::dropIfExists('product_skus');
    }
}
