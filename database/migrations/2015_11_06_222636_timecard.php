<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Timecard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * create time_card table
         */
        Schema::create('time_card', function(Blueprint $table){
            $table->increments('id');
            $table->timestamp('DOW_01');
            $table->timestamp('DOW_02');
            $table->timestamp('DOW_03');
            $table->timestamp('DOW_04');
            $table->timestamp('DOW_05');
            $table->timestamp('DOW_06');
            $table->timestamp('DOW_07');
            $table->timestamps();
        });

        /**
         * create foreign key to the task table
         */
        Schema::table('time_card', function(Blueprint $table) {
            $table->integer('task_id')->unsigned();
            $table->foreign('task_id')->references('id')->on('task');
        });

//        /**
//         * create foreign key to the time_card_attributes table
//         */
//        Schema::table('time_card_attributes', function(Blueprint $table) {
//            $table->integer('time_card_id')->unsigned();
//            $table->foreign('time_card_id')->references('id')->on('time_card');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('time_card');
    }
}
