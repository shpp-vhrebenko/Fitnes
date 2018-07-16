<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned()->default(1);
            $table->foreign('course_id')->references('id')->on('courses');
            $table->integer('item_id')->unsigned()->default(1);
            $table->foreign('item_id')->references('id')->on('items');
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
        Schema::dropIfExists('course_item');
    }
}
