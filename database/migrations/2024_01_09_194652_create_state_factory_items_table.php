<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStateFactoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_factory_items', function (Blueprint $table) {
            $table->id();
            $table->integer('state_factory_id');
            $table->string('name')->comment('状态名称');
            $table->integer('sort')->comment('排序，数字越小越靠前');
            $table->integer('condition_type')->nullable()->comment('完成条件 1-全部完成 2-任一完成');
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
        Schema::dropIfExists('state_factory_items');
    }
}
