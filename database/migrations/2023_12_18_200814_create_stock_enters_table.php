<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockEntersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_enters', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('no')->comment('入库单号');
            $table->integer('type_id')->comment('入库类型');
            $table->timestamp('enter_at')->comment('入库时间');
            $table->text('description')->nullable()->comment('备注');
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
        Schema::dropIfExists('stock_enters');
    }
}
