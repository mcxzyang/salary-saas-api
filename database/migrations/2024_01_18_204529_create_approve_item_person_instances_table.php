<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApproveItemPersonInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approve_item_person_instances', function (Blueprint $table) {
            $table->id();
            $table->integer('approve_item_id');
            $table->integer('approve_item_person_id');
            $table->integer('approve_item_instance_id');
            $table->integer('company_id');
            $table->integer('company_user_id');
            $table->integer('result')->nullable()->comment('审核结果 1-通过 2-拒绝');
            $table->string('reject_reason')->nullable()->comment('拒绝理由');
            $table->text('remark')->nullable()->comment('备注');
            $table->timestamp('approve_at')->nullable()->comment('审核时间');
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
        Schema::dropIfExists('approve_item_person_instances');
    }
}
