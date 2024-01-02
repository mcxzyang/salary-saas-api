<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defectives', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('no')->comment('编号');
            $table->string('name')->comment('名称');
            $table->string('images')->nullable()->comment('多图片');
            $table->string('attachments')->nullable()->comment('附件');
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
        Schema::dropIfExists('defectives');
    }
}
