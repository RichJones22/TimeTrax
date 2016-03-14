<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TimeCardHoursWorked extends Migration
{
    /**
     * abstract:
     *          why is there a time_card_hours_worked table.  originally, the thought was to separate the time on
     *          the task table from that on the time_card table, hence the time_card_hours_worked table.  The idea
     *          being that you could just add hours to the time_card table, as you would in a classical time card; but,
     *          you could also add hours to the task table, if you wanted to break those hours down by task.  In
     *          order to do this, hours would need to be recorded separately, once on the time_card table as an aggregate
     *          for the day, and then again on the task table, as a breakdown by task.
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
        Schema::create('time_card_hours_worked', function(Blueprint $table){
            $table->increments('id');
            $table->date('date_worked');
            $table->char('dow',3);
            $table->float('hours_worked');
            $table->timestamps();
            $table->unique(['date_worked', 'dow']);
        });

        /**
         * create foreign key to the time_card table
         */
//        Schema::table('time_card_hours_worked', function(Blueprint $table) {
//            $table->integer('time_card_id')->unsigned()->nullable();
//            $table->foreign('time_card_id')->references('id')->on('time_card')->nullable();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('time_card_hours_worked');
    }
}
