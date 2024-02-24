<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasePlansTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return  void
    */
    public function up()
    {
        Schema::create('purchase_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('no');
            $table->date('plan_at')->nullable()->comment('计划时间');
            $table->integer('audit_user_id')->nullable()->comment('审核人');
            $table->timestamp('audit_at')->nullable()->comment('审核时间');
            $table->integer('approve_user_id')->nullable()->comment('审批人');
            $table->timestamp('approve_at')->nullable()->comment('审批时间');
            $table->integer('status')->default(0)->comment('状态');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return  void
    */
    public function down()
    {
        Schema::drop('purchase_plans');
    }
};
