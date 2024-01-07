<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterielsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materiels', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('no')->comment('编号');
            $table->string('name')->comment('名称');
            $table->integer('goods_id')->nullable()->comment('关联产品');
            $table->string('spec')->nullable()->comment('规格');
            $table->string('unit')->nullable()->comment('单位');
            $table->integer('stash_id')->nullable()->comment('关联仓库');
            $table->json('images')->nullable()->comment('图片，json');
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
        Schema::dropIfExists('materiels');
    }
}
