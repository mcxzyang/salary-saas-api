<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPassLogsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return  void
    */
    public function up()
    {
        Schema::create('customer_pass_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('customer_id');
            $table->integer('from_user_id')->nullable();
            $table->integer('to_user_id')->nullable();
            $table->text('reason')->nullable()->comment('转交原因');
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
        Schema::drop('customer_pass_logs');
    }
};
