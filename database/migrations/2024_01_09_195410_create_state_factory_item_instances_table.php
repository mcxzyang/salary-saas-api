<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStateFactoryItemInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_factory_item_instances', function (Blueprint $table) {
            $table->id();
            $table->integer('state_factory_id');
            $table->integer('state_factory_item_id');
            $table->integer('state_factory_instance_id');
            $table->string('name');
            $table->integer('sort');
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
        Schema::dropIfExists('state_factory_item_instances');
    }
}
