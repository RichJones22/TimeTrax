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
            $table->date('iso_beginning_dow_date');
            $table->integer('work_id')->unsigned();
            $table->timestamps();
            $table->unique(['iso_beginning_dow_date', 'work_id']);
        });

        /**
         * create foreign key to the time_card_format table
         */
        Schema::table('time_card', function(Blueprint $table) {
            $table->integer('time_card_format_id')->unsigned();
            $table->foreign('time_card_format_id')->references('id')->on('time_card_format');
        });

        /**
         * create foreign key to the work table
         */
        Schema::table('time_card', function(Blueprint $table) {
            $table->foreign('work_id')->references('id')->on('work');
        });

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
