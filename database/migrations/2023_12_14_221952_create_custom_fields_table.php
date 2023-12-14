<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('module_id')->comment('所属模块');
            $table->string('name')->comment('自定义名称');
            $table->integer('type')->comment('自定义类型');
            $table->text('options')->nullable()->comment('自定义信息');
            $table->integer('is_required')->nullable()->comment('是否必填');
            $table->integer('sort')->nullable()->comment('排序 数字越小越靠前');
            $table->integer('status')->default(1)->comment('是否开启');
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
        Schema::dropIfExists('custom_fields');
    }
}
