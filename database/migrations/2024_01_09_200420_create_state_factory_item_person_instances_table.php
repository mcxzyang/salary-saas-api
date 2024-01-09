<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStateFactoryItemPersonInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_factory_item_person_instances', function (Blueprint $table) {
            $table->id();
            $table->integer('state_factory_item_id');
            $table->integer('state_factory_item_person');
            $table->integer('state_factory_item_instance_id');
            $table->integer('company_user_id');
            $table->integer('result')->nullable()->comment('审核结果 1-通过 2-拒绝');
            $table->string('reject_reason')->nullable()->comment('拒绝理由');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('state_factory_item_person_instances');
    }
}
