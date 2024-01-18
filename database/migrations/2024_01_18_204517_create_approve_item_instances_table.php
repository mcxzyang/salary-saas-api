<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApproveItemInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approve_item_instances', function (Blueprint $table) {
            $table->id();
            $table->integer('approve_id');
            $table->integer('approve_item_id');
            $table->integer('approve_instance_id');
            $table->integer('sort');
            $table->integer('condition_type')->nullable();
            $table->integer('status')->default(0)->comment('0-等待开始 1-进行中 2-已完成');
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
        Schema::dropIfExists('approve_item_instances');
    }
}
