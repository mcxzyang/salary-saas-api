<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStashTakeStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stash_take_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('no')->nullable()->comment('判断编号');
            $table->integer('stash_id')->comment('仓库');
            $table->timestamp('take_stock_at')->nullable()->comment('盘点时间');
            $table->integer('created_by')->comment('创建人');
            $table->string('remark')->nullable()->comment('备注');
            $table->integer('status')->default(1)->comment('状态');
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
        Schema::dropIfExists('stash_take_stocks');
    }
}
