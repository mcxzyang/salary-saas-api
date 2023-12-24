<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stashes', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('no')->nullable()->comment('仓库编码');
            $table->string('name')->comment('仓库名称');
            $table->integer('created_by');
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
        Schema::dropIfExists('stashes');
    }
}
