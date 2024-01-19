<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyOptionsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return  void
    */
    public function up()
    {
        Schema::create('company_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('功能名称');
            $table->string('code')->unique()->comment('编码');
            $table->string('description')->nullable()->comment('说明');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::drop('company_options');
    }
};
