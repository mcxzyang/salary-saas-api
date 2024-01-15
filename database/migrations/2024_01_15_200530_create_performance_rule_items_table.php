<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('performance_rule_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('performance_rule_id');
            $table->integer('gradient_id')->comment('梯度名称id 1-核工数 2-月度小时总数');
            $table->integer('operate_id')->comment('操作符 1-大于 2-小于 3-大于等于 4-小于等于');
            $table->integer('conditional_value')->comment('条件值');
            $table->decimal('price')->comment('单价');
            $table->decimal('base_salary')->nullable()->comment('底薪');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_rule_items');
    }
};
