<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkorderTaskReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workorder_task_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('workorder_id');
            $table->integer('workorder_task_id');
            $table->integer('created_by');
            $table->json('product_person_ids')->nullable()->comment('生产人员id，json');
            $table->integer('report_call_number')->comment('报工数');
            $table->integer('good_product_number')->comment('良品数');
            $table->integer('ungood_product_number')->nullable()->comment('不良品数');
            $table->timestamp('start_at')->nullable()->comment('开始时间');
            $table->timestamp('end_at')->nullable()->comment('结束时间');
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
        Schema::dropIfExists('workorder_task_reports');
    }
}
