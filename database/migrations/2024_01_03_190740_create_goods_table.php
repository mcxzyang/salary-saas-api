<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('no')->comment('编号');
            $table->string('name')->comment('产品名称');
            $table->string('specification')->nullable()->comment('规格');
            $table->string('unit')->comment('库存但我');
            $table->integer('working_technology_id')->nullable()->comment('工艺路线');
            $table->integer('type')->comment('产品属性 1-自制 2-外购');
            $table->integer('max_stock')->nullable()->comment('最大库存');
            $table->integer('min_stock')->nullable()->comment('最小库存');
            $table->integer('safe_stock')->nullable()->comment('安全库存');
            $table->integer('stock_number')->nullable()->comment('库存');
            $table->string('images')->nullable()->comment('成品图');
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
        Schema::dropIfExists('goods');
    }
}
