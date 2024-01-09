<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('state_factory_instance_id')->comment('状态工厂实例id');
            $table->integer('current_state_factory_item_instance_id')->comment('当前实例状态id');
            $table->string('no')->comment('编号');
            $table->integer('customer_id')->comment('客户id');
            $table->timestamp('order_at')->nullable()->comment('下单时间');
            $table->timestamp('turnover_at')->nullable()->comment('交付时间');
            $table->integer('payment_type')->nullable()->comment('1-零售 2-月结 3-预付 4-批发 5-其他');
            $table->integer('company_user_id')->comment('订单创建人');
            $table->json('billing_address')->nullable()->comment('开票地址');
            $table->decimal('total', 10)->nullable()->comment('订单总额');
            $table->decimal('received_amount', 10)->nullable()->comment('已收金额');
            $table->integer('if_invoice')->default(0)->comment('是否开票');
            $table->string('remark')->nullable()->comment('备注');
            $table->integer('is_deleted')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
