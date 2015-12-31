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
            $table->date('date_worked')->index();
            $table->char('dow',10);
            $table->float('total_hours_worked');
            $table->timestamps();
        });

        /**
         * create foreign key to the time_card_format table
         */
        Schema::table('time_card', function(Blueprint $table) {
            $table->integer('time_card_format_id')->unsigned();
            $table->foreign('time_card_format_id')->references('id')->on('time_card_format');
        });

        /**
         * create foreign key to the time_card_format table
         */
        Schema::table('time_card', function(Blueprint $table) {
            $table->integer('work_id')->unsigned();
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
