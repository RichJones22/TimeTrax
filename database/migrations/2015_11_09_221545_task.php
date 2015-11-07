<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Task extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * create task table
         */
        Schema::create('task', function (Blueprint $table) {
            $table->increments('id');
            $table->time('start_time_task');
            $table->time('end_time_task');
            $table->integer('task_hours_worked');
            $table->timestamps();
        });

        /**
         * create foreign key to the task table
         */
        Schema::table('task', function (Blueprint $table) {
            $table->integer('time_card_id')->unsigned();
            $table->foreign('time_card_id')->references('id')->on('time_card');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('task');
    }
}
