<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TestingSeleniumVariables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (App::environment('local', 'staging')) {
            /**
             * create task table
             */
            Schema::create('testing_selenium_variables', function(Blueprint $table){
                $table->increments('id');
                $table->boolean('testingRDBMS')->default(0);
                $table->boolean('ttvTypeClearText')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testing_selenium_variables');
    }
}
