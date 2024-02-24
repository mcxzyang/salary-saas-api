<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return  void
    */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('purchase_plan_id')->nullable()->comment('采购计划');
            $table->string('no');
            $table->integer('vendor_id')->comment('供应商');
            $table->string('linkman')->nullable()->comment('联系人');
            $table->string('phone')->nullable()->comment('联系电话');
            $table->string('fax')->nullable()->comment('传真');
            $table->string('delivery_type')->nullable()->comment('交货方式');
            $table->timestamp('exp_at')->nullable()->comment('过期时间');
            $table->integer('audit_user_id')->nullable()->comment('审核人');
            $table->timestamp('audit_at')->nullable()->comment('审核时间');
            $table->integer('approve_user_id')->nullable()->comment('审批人');
            $table->timestamp('approve_at')->nullable()->comment('审批时间');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::drop('purchase_orders');
    }
};
