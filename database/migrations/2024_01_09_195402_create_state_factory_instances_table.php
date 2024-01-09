<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStateFactoryInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_factory_instances', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('state_factory_id');
            $table->string('model_type')->comment('模型');
            $table->integer('model_id')->comment('模型id');
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
        Schema::dropIfExists('state_factory_instances');
    }
}
