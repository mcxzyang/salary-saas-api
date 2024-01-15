<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('performance_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('绩效名称');
            $table->string('no')->comment('编号');
            $table->integer('type')->comment('绩效类型');
            $table->integer('goods_id')->nullable()->comment('产品');
            $table->integer('working_process_id')->nullable()->comment('工序');
            $table->integer('salary_type')->comment('计薪方式');
            $table->decimal('basic_salary')->nullable()->comment('底薪');
            $table->decimal('price')->comment('单价');
            $table->integer('unit')->nullable()->comment('单价的单位');
            $table->integer('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_rules');
    }
};
