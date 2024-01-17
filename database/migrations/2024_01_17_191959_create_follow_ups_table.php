<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowUpsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return  void
    */
    public function up()
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('customer_id');
            $table->integer('type_id')->comment('跟进方式 字典值');
            $table->text('content')->comment('跟进内容');
            $table->timestamp('next_follow_up_at')->comment('下次跟进时间');
            $table->json('images')->nullable()->comment('相关图片');
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
        Schema::drop('follow_ups');
    }
};
