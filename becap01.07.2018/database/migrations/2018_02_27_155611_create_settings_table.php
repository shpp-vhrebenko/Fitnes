<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');            
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();           
            $table->string('title_site');
            $table->string('owner');
            $table->string('address');           
            $table->string('email');
            $table->string('phone');
            $table->string('favicon');
            $table->string('logo');                       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
