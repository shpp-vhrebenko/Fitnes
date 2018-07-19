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
            $table->string('slug')->nullable(); 
            $table->boolean('is_active')->default(true);
            $table->string('whats_app_link')->nullable();
            $table->text('faq');
            $table->integer('notification_day_number')->unsigned()->default(2);
            $table->text('notification');
            $table->text('food_regulations')->nullable();
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
