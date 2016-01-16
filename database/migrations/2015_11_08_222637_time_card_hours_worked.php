<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TimeCardHoursWorked extends Migration
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
        Schema::create('time_card_hours_worked', function(Blueprint $table){
            $table->increments('id');
            $table->integer('work_id')->unsigned()->nullable();
            $table->date('date_worked');
            $table->char('dow',3);
            $table->float('hours_worked');
            $table->timestamps();
//            $table->unique(['work_id', 'id']);
        });

        /**
         * create foreign key to the time_card table
         */
        Schema::table('time_card_hours_worked', function(Blueprint $table) {
            $table->foreign('work_id')->references('work_id')->on('time_card')->nullable();
        });
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
