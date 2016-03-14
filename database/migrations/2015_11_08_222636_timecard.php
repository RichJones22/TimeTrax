<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Timecard extends Migration
{
    /**
     * abstract:
     *          why duplicate hours_worked on both the time_card and task tables. The idea being that you could just
     *          add hours to the time_card table, as you would in a classical time card; but, you could also add hours
     *          to the task table, if you wanted to break those hours down by task.  In order to do this, hours would
     *          need to be recorded separately, once on the time_card table as an aggregate for the day, and then again
     *          on the task table, as a breakdown by task.
     */

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
            $table->integer('work_id')->unsigned();
            $table->date('date_worked');
            $table->float('hours_worked');
            $table->timestamps();
            $table->unique(['work_id', 'date_worked']);
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
