<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('icon')->default('icon_course.png');
            $table->integer('price')->unsigned()->default(1);
            $table->date('date_start_selection')->nullable();
            $table->date('date_end_selection')->nullable();
            $table->string('type')->default('cours');
            $table->integer('period');
            $table->boolean('is_active')->default(true);       
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
        Schema::dropIfExists('courses');
    }
}
