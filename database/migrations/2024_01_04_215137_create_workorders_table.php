<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workorders', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('no')->comment('编号');
            $table->integer('goods_id')->comment('产品id');
            $table->integer('planned_number')->comment('计划数');
            $table->timestamp('plan_start_at')->comment('计划开始时间');
            $table->timestamp('plan_end_at')->comment('计划结束时间');
            $table->integer('status')->default(1)->comment('状态 1-未开始 2-进行中 3-已完成 4-已撤回 5-已取消');
            $table->integer('is_deleted')->default(0);
            $table->text('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('workorders');
    }
}
