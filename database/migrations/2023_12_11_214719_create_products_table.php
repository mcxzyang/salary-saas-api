<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('title');
            $table->string('image')->nullable()->comment('主图');
            $table->text('carousel_images')->nullable()->comment('轮播图');
            $table->string('product_number')->unique()->nullable()->comment('上哦编码');
            $table->integer('category_id')->nullable()->comment('分类');
            $table->integer('sales_number')->default(0)->comment('销量');
            $table->integer('view_number')->default(0)->comment('点击量');
            $table->text('content')->comment('内容');
            $table->integer('sort')->nullable()->comment('排序，数字越大越靠前');
            $table->integer('status')->default(1)->comment('1-上架 0-下架');
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
        Schema::dropIfExists('products');
    }
}
