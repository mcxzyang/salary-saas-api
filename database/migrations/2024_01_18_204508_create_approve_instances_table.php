<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApproveInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approve_instances', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('approve_id');
            $table->string('model_type');
            $table->integer('model_id');
            $table->integer('status')->default(1)->comment('1-进行中 2-已完成');
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
        Schema::dropIfExists('approve_instances');
    }
}
