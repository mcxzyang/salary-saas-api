<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsCategoriesTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return  void
    */
    public function up()
    {
        Schema::create('goods_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('pid')->default(0);
            $table->string('name');
            $table->string('image')->nullable();
            $table->integer('status')->default(1);
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
        Schema::drop('goods_categories');
    }
};
